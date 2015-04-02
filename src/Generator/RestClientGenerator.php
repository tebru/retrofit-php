<?php
/**
 * File RestClientGenerator.php
 */

namespace Tebru\Retrofit\Generator;

use Doctrine\Common\Annotations\AnnotationReader;
use LogicException;
use ReflectionClass;
use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\Headers;
use Tebru\Retrofit\Annotation\JsonBody;
use Tebru\Retrofit\Annotation\QueryMap;
use Tebru\Retrofit\Annotation\Returns;
use Tebru\Retrofit\Annotation\AnnotationToVariableMap;
use Tebru\Retrofit\Annotation\HttpRequest;
use Twig_Environment;

/**
 * Class RestClientGenerator
 *
 * Generates a REST client based on an interface name
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RestClientGenerator
{
    /**
     * @var Twig_Environment $twig
     */
    private $twig;

    /**
     * Constructor
     *
     * @param Twig_Environment $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Generate a REST client based on an interface
     *
     * @param string $interface
     * @return string
     */
    public function generate($interface)
    {
        // use reflection to inspect the interface
        $reader = new AnnotationReader();
        $reflectionClass = new ReflectionClass($interface);

        // get information about the class
        $interfaceName = $reflectionClass->getName();
        $className = 'Generated_' . md5($interfaceName);
        $fileName = $reflectionClass->getFileName();

        // get use statements as array
        $useRegex = '/^use(?:\s)+(?!Tebru\\\\Retrofit\\\\Annotation).+;$/m';
        preg_match_all($useRegex, file_get_contents($reflectionClass->getFileName()), $matches);
        $useStatements = $matches[0];

        // loop through class annotations
        $classHeaders = ['headers' => []];
        foreach ($reader->getClassAnnotations($reflectionClass) as $classAnnotation) {
            $classHeaders = $this->headersAnnotation($classAnnotation, $classHeaders);
        }


        // loop over class methods
        $methods = [];
        foreach ($reflectionClass->getMethods() as $index => $classMethod) {
            $parameters = $classMethod->getParameters();

            // initialize data array
            $method = [
                'name' => $classMethod->getShortName(),
                'type' => '',
                'path' => '',
                'return' => 'array',
                'methodDeclaration' => '',
                'options' => [],
                'parts' => [],
                'query' => [],
                'queryMap' => [],
                'headers' => [],
                'jsonBody' => false,
            ];

            // loop through method annotations
            foreach ($reader->getMethodAnnotations($classMethod) as $methodAnnotation) {
                $method = $this->getMethodDeclaration($method, $fileName);

                // check each annotation type expected
                $method = $this->httpRequestAnnotation($methodAnnotation, $method, $parameters);
                $method = $this->configureMethod($methodAnnotation, $method, $parameters, '\Tebru\Retrofit\Annotation\Query', 'query');
                $method = $this->configureMethod($methodAnnotation, $method, $parameters, '\Tebru\Retrofit\Annotation\Part', 'parts');
                $method = $this->configureMethod($methodAnnotation, $method, $parameters, '\Tebru\Retrofit\Annotation\Header', 'headers');
                $method = $this->bodyAnnotation($methodAnnotation, $method, $parameters);
                $method = $this->queryMapAnnotation($methodAnnotation, $method, $parameters);
                $method = $this->headersAnnotation($methodAnnotation, $method);
                $method = $this->returnsAnnotation($methodAnnotation, $method);
                $method = $this->jsonBodyAnnotation($methodAnnotation, $method);
            }

            // merge class headers array with method headers
            $method['headers'] = array_merge($classHeaders['headers'], $method['headers']);

            if (empty($method['type'])) {
                throw new LogicException('Request method must be set');
            }

            if (!empty($method['options']['body']) && !empty($method['parts'])) {
                throw new LogicException('Body and part cannot both be set');
            }

            $methods[$index] = $method;
        }

        // render class as string
        $template = $this->twig->loadTemplate('service.php.twig');

        return $template->render([
            'uses' => $useStatements,
            'className' => $className,
            'interfaceName' => $interfaceName,
            'methods' => $methods,
        ]);
    }

    /**
     * Add method declaration to array
     *
     * @param array $method
     * @param string $fileName
     * @return array
     */
    private function getMethodDeclaration(array $method, $fileName)
    {
        $regex = sprintf('/^.*(?:function %s[\s]?\().*/m', $method['name']);
        preg_match($regex, file_get_contents($fileName), $methodDeclaration);
        $methodDeclaration = $methodDeclaration[0];
        $methodDeclaration = str_replace(';', '', $methodDeclaration);
        $method['methodDeclaration'] = trim($methodDeclaration);

        return $method;
    }

    /**
     * Configures array for http request annotations
     *
     * @param mixed $annotation
     * @param array $method
     * @param array $parameters
     * @return array
     */
    private function httpRequestAnnotation($annotation, array $method, array $parameters)
    {
        if (!$annotation instanceof HttpRequest) {
            return $method;
        }

        foreach ($annotation->getParameters() as $parameter) {
            $this->assertParameter($parameters, $parameter);
        }

        $method['type'] = $annotation->getType();
        $method['path'] = $annotation->getPath();
        $method['query'] = array_merge($method['query'], $annotation->getQueries());

        return $method;
    }

    /**
     * Generic method to configure the method array
     * @param mixed $annotation
     * @param array $method
     * @param array $methodParameters
     * @param $expectedAnnotation
     * @param string $key
     * @return array
     */
    private function configureMethod($annotation, array $method, array $methodParameters, $expectedAnnotation, $key)
    {
        if (!$annotation instanceof $expectedAnnotation) {
            return $method;
        }

        /** @var AnnotationToVariableMap $annotation */
        $paramName = substr($annotation->getValue(), 1);
        $this->assertParameter($methodParameters, $paramName);

        $method[$key][$annotation->getKey()] = $annotation->getValue();

        return $method;
    }

    /**
     * Configures array for body annotation
     *
     * @param mixed $annotation
     * @param array $method
     * @param array $methodParameters
     * @return array
     */
    private function bodyAnnotation($annotation, array $method, array $methodParameters)
    {
        if (!$annotation instanceof Body) {
            return $method;
        }

        $paramName = substr($annotation->getValue(), 1);
        $this->assertParameter($methodParameters, $paramName);

        $method['options']['body'] = $annotation->getValue();

        return $method;
    }

    /**
     * Configure array for query map
     *
     * @param mixed $methodAnnotation
     * @param array $method
     * @param array $parameters
     * @return array
     */
    private function queryMapAnnotation($methodAnnotation, array $method, array $parameters)
    {
        if (!$methodAnnotation instanceof QueryMap) {
            return $method;
        }

        $paramName = substr($methodAnnotation->getValue(), 1);
        $this->assertParameter($parameters, $paramName);

        $method['query'][] = $methodAnnotation->getValue();

        return $method;
    }

    /**
     * Configures array for headers annotation
     *
     * @param mixed $methodAnnotation
     * @param array $method
     * @return array
     */
    private function headersAnnotation($methodAnnotation, array $method)
    {
        if (!$methodAnnotation instanceof Headers) {
            return $method;
        }

        // merge headers with existing headers
        $method['headers'] = array_merge($method['headers'], $methodAnnotation->getHeaders());

        return $method;
    }

    /**
     * Configures method for returns annotation
     *
     * @param mixed $methodAnnotation
     * @param array $method
     * @return array
     */
    private function returnsAnnotation($methodAnnotation, array $method)
    {
        if (!$methodAnnotation instanceof Returns) {
            return $method;
        }

        $method['return'] = $methodAnnotation->getReturn();

        return $method;
    }

    /**
     * Sets json body to true if annotation exists
     *
     * @param mixed $methodAnnotation
     * @param array $method
     * @return array
     */
    private function jsonBodyAnnotation($methodAnnotation, array $method)
    {
        if (!$methodAnnotation instanceof JsonBody) {
            return $method;
        }

        $method['jsonBody'] = true;

        return $method;
    }

    /**
     * Asserts that a parameter exists on the method
     *
     * @param array $parameters
     * @param string $name
     * @return null
     */
    private function assertParameter(array $parameters, $name)
    {
        /** @var \ReflectionParameter $parameter */
        foreach ($parameters as $parameter) {
            if ($parameter->getName() === $name) {
                return null;
            }
        }

        throw new LogicException('Parameter not found');
    }
}

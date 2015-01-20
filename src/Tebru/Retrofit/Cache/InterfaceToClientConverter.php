<?php
/**
 * File InterfaceToClientConverter.php
 */

namespace Tebru\Retrofit\Cache;

use Doctrine\Common\Annotations\AnnotationReader;
use LogicException;
use ReflectionClass;
use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\Header;
use Tebru\Retrofit\Annotation\Headers;
use Tebru\Retrofit\Annotation\Part;
use Tebru\Retrofit\Annotation\Query;
use Tebru\Retrofit\Annotation\QueryMap;
use Tebru\Retrofit\Annotation\Returns;
use Tebru\Retrofit\HttpRequest;
use Tebru\Retrofit\Twig\PrintArrayFunction;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_SimpleFunction;

/**
 * Class InterfaceToClientConverter
 *
 * Converts an interface into a REST client based on annotations
 *
 * @author Nate Brunette <n@tebru.net>
 */
class InterfaceToClientConverter
{
    /**
     * @var Twig_Environment $twig
     */
    private $twig;

    /**
     * Constructor
     */
    public function __construct()
    {
        // set up twig environment
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../Resources/Template');
        $this->twig = new Twig_Environment($loader);
        $this->twig->addFunction(new Twig_SimpleFunction('print_array', new PrintArrayFunction()));
    }

    /**
     * Create a REST client based on an interface
     *
     * @param string $interface
     * @return string
     */
    public function createRestClient($interface) {
        // use reflection to inspect the interface
        $reader = new AnnotationReader();
        $reflectionClass = new ReflectionClass($interface);

        // get information about the class
        $interfaceName = $reflectionClass->getName();
        $className = 'Generated_' . md5($reflectionClass->getShortName());
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
        foreach ($reflectionClass->getMethods() as $index => $method) {
            $parameters = $method->getParameters();

            // initialize data array
            $methods[$index]['name'] = $method->getShortName();
            $methods[$index]['type'] = '';
            $methods[$index]['path'] = '';
            $methods[$index]['return'] = 'array';
            $methods[$index]['methodDeclaration'] = '';
            $methods[$index]['options'] = [];
            $methods[$index]['parts'] = [];
            $methods[$index]['query'] = [];
            $methods[$index]['queryMap'] = [];
            $methods[$index]['headers'] = [];

            // loop through method annotations
            foreach ($reader->getMethodAnnotations($method) as $methodAnnotation) {
                // check each annotation type expected
                $methods[$index] = $this->httpRequestAnnotation($methodAnnotation, $methods[$index], $fileName, $parameters);
                $methods[$index] = $this->queryAnnotation($methodAnnotation, $methods[$index], $parameters);
                $methods[$index] = $this->queryMapAnnotation($methodAnnotation, $methods[$index], $parameters);
                $methods[$index] = $this->bodyAnnotation($methodAnnotation, $methods[$index], $parameters);
                $methods[$index] = $this->partAnnotation($methodAnnotation, $methods[$index], $parameters);
                $methods[$index] = $this->headersAnnotation($methodAnnotation, $methods[$index]);
                $methods[$index] = $this->headerAnnotation($methodAnnotation, $methods[$index], $parameters);
                $methods[$index] = $this->returnsAnnotation($methodAnnotation, $methods[$index]);
            }

            // merge class headers array with method headers
            $methods[$index]['headers'] = array_merge($classHeaders['headers'], $methods[$index]['headers']);

            if (empty($methods[$index]['type'])) {
                throw new LogicException('Request method must be set');
            }

            if (!empty($methods[$index]['options']['body']) && !empty($methods[$index]['parts'])) {
                throw new LogicException('Body and part cannot both be set');
            }
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
     * Configures array for http request annotations (@GET, @POST, etc)
     *
     * @param mixed $methodAnnotation
     * @param array $method
     * @param $fileName
     * @param array $parameters
     * @return array
     */
    private function httpRequestAnnotation($methodAnnotation, array $method, $fileName, array $parameters)
    {
        if (!$methodAnnotation instanceof HttpRequest) {
            return $method;
        }

        // set type
        $method['type'] = $methodAnnotation->getType();

        // start setting path, replacing {} if needed
        $path = $methodAnnotation->getPath();
        if (preg_match_all('/{(.+?)}/', $path, $matches)) {
            // loop over each match, which will have two sub-arrays with the matched text
            // in the first array and the captures in the second for each match
            foreach ($matches[0] as $key => $match) {
                // set replace value (the matched text)
                $replace = $matches[0][$key];

                // ensure that parameter exists
                $name = $matches[1][$key];
                $this->assertParameter($parameters, [$name]);

                // set the replacement text (matched text without {}, prepended with $)
                $param = '$' . $name;

                $path = str_replace($replace, $param, $path);
            }
        }

        // check for query string in path
        $queryString = strstr($path, '?');
        if (false !== $queryString) {
            // remove ? and everything after
            $path = substr($path, 0, -strlen($queryString));

            // set $queryString to everything after the ?
            $queryString = substr($queryString, 1);

            // convert string to array and set to $stringAsArray
            parse_str($queryString, $stringAsArray);
            $method['query'] = array_merge($method['query'], $stringAsArray);
        }

        // set path
        $method['path'] = $path;

        // get the method declaration from the file
        $regex = sprintf('/^.*(?:function %s[\s]?\().*/m', $method['name']);
        preg_match($regex, file_get_contents($fileName), $methodDeclaration);
        $method['methodDeclaration'] = trim(substr($methodDeclaration[0], 0, -1));

        return $method;
    }

    /**
     * Configures array for query parameters annotation
     *
     * @param mixed $methodAnnotation
     * @param array $method
     * @param array $parameters
     * @return array
     */
    private function queryAnnotation($methodAnnotation, array $method, array $parameters)
    {
        if (!$methodAnnotation instanceof Query) {
            return $method;
        }

        // ensure that parameter exists
        $query = $methodAnnotation->getQuery();
        $this->assertParameter($parameters, [substr(array_shift($query), 1)]);

        // merge with current query array
        $method['query'] = array_merge($method['query'], $methodAnnotation->getQuery());

        return $method;
    }

    /**
     * Configures array for query map annotation
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

        // ensure that parameter exists
        $this->assertParameter($parameters, [substr($methodAnnotation->getQueryMap(), 1)]);

        // add map to current query list
        $method['query'][] = $methodAnnotation->getQueryMap();

        return $method;
    }

    /**
     * Configures array for body annotation
     *
     * @param mixed $methodAnnotation
     * @param array $method
     * @param array $parameters
     * @return array
     */
    private function bodyAnnotation($methodAnnotation, array $method, array $parameters)
    {
        if (!$methodAnnotation instanceof Body) {
            return $method;
        }

        // ensure parameter exists
        $this->assertParameter($parameters, [substr($methodAnnotation->getBody(), 1)]);

        $method['options']['body'] = $methodAnnotation->getBody();

        return $method;
    }

    /**
     * Configures array for body parts annotation
     *
     * @param mixed $methodAnnotation
     * @param array $method
     * @param array $parameters
     * @return array
     */
    private function partAnnotation($methodAnnotation, array $method, array $parameters)
    {
        if (!$methodAnnotation instanceof Part) {
            return $method;
        }

        // ensure parameter exists
        $this->assertParameter($parameters, [substr($methodAnnotation->getPart(), 1)]);

        $method['parts'][$methodAnnotation->getKey()] = $methodAnnotation->getPart();

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
     * Configures array for header annotation
     *
     * @param mixed $methodAnnotation
     * @param array $method
     * @param array $parameters
     * @return array
     */
    private function headerAnnotation($methodAnnotation, array $method, array $parameters)
    {
        if (!$methodAnnotation instanceof Header) {
            return $method;
        }

        // ensure parameter exists
        $header = $methodAnnotation->getHeader();
        $this->assertParameter($parameters, [substr(array_shift($header), 1)]);

        // merge header with existing headers
        $method['headers'] = array_merge($method['headers'], $methodAnnotation->getHeader());

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
     * Asserts that a parameter exists on the method
     *
     * @param array $parameters
     * @param array $keys
     * @return null
     * @throws LogicException if parameter doesn't exist
     */
    private function assertParameter(array $parameters, array $keys)
    {
        /** @var \ReflectionParameter $parameter */
        foreach ($parameters as $parameter) {
            if (in_array($parameter->getName(), $keys)) {
                return null;
            }
        }

        throw new LogicException('Parameter not found');
    }
}

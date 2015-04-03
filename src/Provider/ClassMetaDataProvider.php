<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Provider;

use Doctrine\Common\Annotations\AnnotationReader;
use LogicException;
use PhpParser\Lexer;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Parser;
use ReflectionClass;
use ReflectionMethod;
use Tebru;

/**
 * Class ClassMetaDataProvider
 *
 * This class adapts the PHP parser array after it parses the interface.  It expects
 * the result of parsing a full interface file.
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ClassMetaDataProvider
{
    /**
     * A reflection class based on class name provider
     *
     * @var ReflectionClass
     */
    private $reflectionClass;

    /**
     * An instance of the doctrine annotation reader to provider
     * information about annotations
     *
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * The namespace statement
     *
     * @var Namespace_
     */
    private $namespace;

    /**
     * The interface statement
     *
     * @var Interface_
     */
    private $interface;

    /**
     * The namespace as a string
     *
     * @var string
     */
    private $namespaceName;

    /**
     * An array of use statements
     *
     * These represent one line and can be used during code generation
     *
     * @var array
     */
    private $useStatements = [];

    /**
     * The non-namespaced version of the interface name
     *
     * @var string
     */
    private $interfaceNameShort;

    /**
     * The namespaced version of the interface name
     *
     * @var string
     */
    private $interfaceNameFull;

    /**
     * An array of interface method lines
     *
     * These can be used during code generation
     *
     * @var array
     */
    private $interfaceMethods = [];

    /**
     * Constructor
     *
     * We expect $statements to be an array with 1 node that is a namespace type
     *
     * @param string $interfaceName
     */
    public function __construct($interfaceName)
    {
        $this->reflectionClass = new ReflectionClass($interfaceName);
        $this->annotationReader = new AnnotationReader();

        // use the php parser internally to cache the class data
        $this->namespace = $this->parse();
    }

    /**
     * Takes an interface name and stores the output of the php parser
     *
     * @return Namespace_
     */
    private function parse()
    {
        $file = file_get_contents($this->getFilename());
        $parser = new Parser(new Lexer());
        $statements = $parser->parse($file);

        Tebru\assert(1 === count($statements), new LogicException('$statements must be an array with one element'));
        Tebru\assert($statements[0] instanceof Namespace_, new LogicException('Expecting Namespace_ statement'));

        $namespace = $statements[0];

        return $namespace;
    }

    /**
     * Returns the reflection class' filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->reflectionClass->getFileName();
    }

    /**
     * Uses the annotation reader to get the reflection class' annotations
     *
     * @return array
     */
    public function getClassAnnotations()
    {
        return $this->annotationReader->getClassAnnotations($this->reflectionClass);
    }

    /**
     * Uses the annotation reader to get the reflection class method's annotations
     * @param ReflectionMethod $reflectionMethod
     * @return array
     */
    public function getMethodAnnotations(ReflectionMethod $reflectionMethod)
    {
        return $this->annotationReader->getMethodAnnotations($reflectionMethod);
    }

    /**
     * @return ReflectionMethod[]
     */
    public function getReflectionMethods()
    {
        return $this->reflectionClass->getMethods();
    }

    /**
     * Returns the interface namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        if (null !== $this->namespaceName) {
            return $this->namespaceName;
        }

        $this->namespaceName = $this->createClassName($this->namespace);

        return $this->namespaceName;
    }

    /**
     * Returns an array of use statements for code generation
     *
     * @return array
     */
    public function getUseStatements()
    {
        if (!empty($this->useStatements)) {
            return $this->useStatements;
        }

        $useStatements = array_filter($this->namespace->stmts, function ($element) {
            return $element instanceof Use_;
        });

        /** @var Use_ $useStatement */
        foreach ($useStatements as $useStatement) {
            foreach ($useStatement->uses as $use) {
                $classname = $this->createClassName($use);
                $this->useStatements[] = sprintf('use %s as %s;', $classname, $use->alias);
            }
        }

        return $this->useStatements;
    }

    /**
     * Returns the non-namespaced version of the interface name
     *
     * @return string
     */
    public function getInterfaceNameShort()
    {
        if (null !== $this->interfaceNameShort) {
            return $this->interfaceNameShort;
        }

        $interface = $this->getInterface();

        $this->interfaceNameShort = $interface->name;

        return $this->interfaceNameShort;
    }

    /**
     * Returns the namespaced version of the interface name
     *
     * @return string
     */
    public function getInterfaceNameFull()
    {
        if (null !== $this->interfaceNameFull) {
            return $this->interfaceNameFull;
        }

        $namespace = $this->getNamespace();
        $className = $this->getInterfaceNameShort();

        return sprintf('%s\%s', $namespace, $className);
    }

    /**
     * Get the array of interface methods
     *
     * @return ClassMethod[]
     */
    public function getInterfaceMethods()
    {
        if (!empty($this->interfaceMethods)) {
            return $this->interfaceMethods;
        }

        $interface = $this->getInterface();
        $this->interfaceMethods = $interface->stmts;

        return $this->interfaceMethods;
    }

    /**
     * Given a ClassMethod, return the method name
     *
     * @param ClassMethod $classMethod
     * @return string
     */
    public function getMethodName(ClassMethod $classMethod)
    {
        return $classMethod->name;
    }

    /**
     * Given a ClassMethod, return the method parameters as a string
     *
     * @param ClassMethod $classMethod
     * @return string
     */
    public function getMethodParameters(ClassMethod $classMethod)
    {
        $methodParams = $classMethod->params;

        /** @var Param $methodParam */
        $params = [];
        foreach ($methodParams as $methodParam) {
            $params[] = $this->getMethodParameter($methodParam);
        }

        return implode(', ', $params);
    }

    /**
     * Given a class method, return the full method declaration as a string
     *
     * @param ClassMethod $classMethod
     * @return string
     */
    public function getMethodDeclaration(ClassMethod $classMethod)
    {
        $staticTypeId = Class_::MODIFIER_PUBLIC + Class_::MODIFIER_STATIC;
        $isStatic = ($staticTypeId === $classMethod->type) ? true : false;
        $staticModifier = ($isStatic) ? 'static ' : '';

        return sprintf('%spublic function %s(%s)', $staticModifier, $this->getMethodName($classMethod), $this->getMethodParameters($classMethod));
    }

    /**
     * Get the name of a statement that has the name property
     *
     * @param Stmt $statement
     * @return string
     */
    private function createClassName(Stmt $statement)
    {
        Tebru\assert(property_exists($statement, 'name'), new LogicException('Object must have a public name property'));
        Tebru\assert($statement->name instanceof Name, new LogicException('Name property must be instance of PhpParser\Node\Name'));

        return (string)$statement->name;
    }

    /**
     * Get the interface statement from the namespace statement
     *
     * @return Interface_
     */
    private function getInterface()
    {
        if (null !== $this->interface) {
            return $this->interface;
        }

        $interface = array_filter($this->namespace->stmts, function ($element) {
            return $element instanceof Interface_;
        });

        Tebru\assert(1 === count($interface), new LogicException('$interface must be an array with one element'));

        $this->interface = array_shift($interface);

        return $this->interface;
    }

    /**
     * Get a method parameter from a Param
     *
     * This will return the type hint and variable name prefixed with a dollar sign
     *
     * @param Param $param
     * @return string
     */
    private function getMethodParameter(Param $param)
    {
        $type = $param->type;
        $name = $param->name;

        $paramString = (null !== $type) ? sprintf('%s ', $type) : '';
        $paramString .= sprintf('$%s', $name);

        return $paramString;
    }

}

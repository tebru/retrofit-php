<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal;

use LogicException;
use ReflectionMethod;
use Tebru\AnnotationReader\AbstractAnnotation;
use Tebru\PhpType\TypeToken;
use Tebru\Retrofit\AnnotationHandler;
use Tebru\Retrofit\Converter;
use Tebru\Retrofit\Internal\Converter\ConverterProvider;
use Tebru\Retrofit\Annotation\ParameterAwareAnnotation;
use Tebru\Retrofit\RequestBodyConverter;
use Tebru\Retrofit\ServiceMethodBuilder;
use Tebru\Retrofit\StringConverter;

/**
 * Class AnnotationProcessor
 *
 * Given an array of handlers, process an [@see AbstractAnnotation]
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class AnnotationProcessor
{
    /**
     * An array of annotation handlers
     *
     * @var AnnotationHandler[]
     */
    private $handlers;

    /**
     * Constructor
     *
     * @param AnnotationHandler[] $handlers
     */
    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * Accepts an annotation and delegates to an [@see AnnotationHandler]
     *
     * @param AbstractAnnotation $annotation
     * @param ServiceMethodBuilder $serviceMethodBuilder
     * @param ConverterProvider $converterProvider
     * @param ReflectionMethod $reflectionMethod
     * @throws \LogicException
     */
    public function process(
        AbstractAnnotation $annotation,
        ServiceMethodBuilder $serviceMethodBuilder,
        ConverterProvider $converterProvider,
        ReflectionMethod $reflectionMethod
    ): void {
        $name = $annotation->getName();

        if (!isset($this->handlers[$name])) {
            return;
        }

        $handler = $this->handlers[$name];
        $converter = null;
        $index = null;

        if ($annotation instanceof ParameterAwareAnnotation) {
            $index = $this->findMethodParameterIndex($reflectionMethod, $annotation->getVariableName());
            $type = $this->getParameterType($reflectionMethod, $index);
            $converter = $this->getConverter($annotation, $converterProvider, $type);
        }

        $handler->handle($annotation, $serviceMethodBuilder, $converter, $index);
    }

    /**
     * Find the position of the method parameter
     *
     * @param ReflectionMethod $reflectionMethod
     * @param string $name
     * @return int
     * @throws \LogicException
     */
    private function findMethodParameterIndex(ReflectionMethod $reflectionMethod, string $name): int
    {
        $reflectionParameters = $reflectionMethod->getParameters();
        foreach ($reflectionParameters as $index => $reflectionParameter) {
            if ($reflectionParameter->name === $name) {
                return $index;
            }
        }

        throw new LogicException(sprintf(
            'Retrofit: Could not find parameter named %s in %s::%s. Please double check that annotations are properly ' .
            'referencing method parameters.',
            $name,
            $reflectionMethod->getDeclaringClass()->name,
            $reflectionMethod->name
        ));
    }

    /**
     * Get the parameter type
     *
     * @param ReflectionMethod $reflectionMethod
     * @param int $index
     * @return TypeToken
     * @throws \LogicException
     */
    private function getParameterType(ReflectionMethod $reflectionMethod, int $index): TypeToken
    {
        $reflectionParameter = $reflectionMethod->getParameters()[$index];
        $reflectionType = $reflectionParameter->getType();

        if ($reflectionType === null) {
            throw new LogicException(sprintf(
                'Retrofit: Parameter type was not found for method %s::%s',
                $reflectionMethod->getDeclaringClass()->name,
                $reflectionMethod->name
            ));
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new TypeToken((string)$reflectionType);
    }

    /**
     * Get the converter from annotation converter class
     *
     * @param ParameterAwareAnnotation $annotation
     * @param ConverterProvider $converterProvider
     * @param TypeToken $type
     * @return Converter
     * @throws \LogicException
     */
    private function getConverter(
        ParameterAwareAnnotation $annotation,
        ConverterProvider $converterProvider,
        TypeToken $type
    ): Converter {
        switch ($annotation->converterType()) {
            case RequestBodyConverter::class:
                return $converterProvider->getRequestBodyConverter($type);
            case StringConverter::class:
                return $converterProvider->getStringConverter($type);
        }

        throw new LogicException(sprintf(
            'Retrofit: Unable to handle converter of type %s. Please use RequestBodyConverter or StringConverter',
            $annotation->converterType()
        ));
    }
}

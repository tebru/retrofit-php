<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\ServiceMethod;

use LogicException;
use Psr\Http\Message\StreamInterface;
use ReflectionMethod;
use Tebru\AnnotationReader\AbstractAnnotation;
use Tebru\AnnotationReader\AnnotationCollection;
use Tebru\AnnotationReader\AnnotationReaderAdapter;
use Tebru\PhpType\TypeToken;
use Tebru\Retrofit\Annotation as Annot;
use Tebru\Retrofit\CallAdapter;
use Tebru\Retrofit\Converter;
use Tebru\Retrofit\Internal\AnnotationProcessor;
use Tebru\Retrofit\Internal\CallAdapter\CallAdapterProvider;
use Tebru\Retrofit\Internal\Converter\ConverterProvider;
use Tebru\Retrofit\ServiceMethodBuilder;

/**
 * Class ServiceMethodFactory
 *
 * Creates and sets up values for [@see ServiceMethodBuilder], then delegates final setup
 * to annotation handles.
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class ServiceMethodFactory
{
    /**
     * Handles an [@see AbstractAnnotation]
     *
     * @var AnnotationProcessor
     */
    private $annotationProcessor;

    /**
     * Fetches a [@see CallAdapter]
     *
     * @var CallAdapterProvider
     */
    private $callAdapterProvider;

    /**
     * Fetches a [@see Converter]
     *
     * @var ConverterProvider
     */
    private $converterProvider;

    /**
     * Reads annotations from service interface
     *
     * @var AnnotationReaderAdapter
     */
    private $annotationReader;

    /**
     * The request base url
     *
     * @var string
     */
    private $baseUrl;

    /**
     * Constructor
     *
     * @param AnnotationProcessor $annotationProcessor
     * @param CallAdapterProvider $callAdapterProvider
     * @param ConverterProvider $converterProvider
     * @param AnnotationReaderAdapter $annotationReader
     * @param string $baseUrl
     */
    public function __construct(
        AnnotationProcessor $annotationProcessor,
        CallAdapterProvider $callAdapterProvider,
        ConverterProvider $converterProvider,
        AnnotationReaderAdapter $annotationReader,
        string $baseUrl
    ) {
        $this->annotationProcessor = $annotationProcessor;
        $this->callAdapterProvider = $callAdapterProvider;
        $this->converterProvider = $converterProvider;
        $this->annotationReader = $annotationReader;
        $this->baseUrl = $baseUrl;
    }

    /**
     * Creates a [@see DefaultServiceMethod]
     *
     * @param string $interfaceName
     * @param string $methodName
     * @return DefaultServiceMethod
     * @throws \LogicException
     */
    public function create(string $interfaceName, string $methodName): DefaultServiceMethod
    {
        $serviceMethodBuilder = new DefaultServiceMethodBuilder();
        $annotations = $this->annotationReader->readMethod($methodName, $interfaceName, true, true);

        $reflectionMethod = new ReflectionMethod($interfaceName, $methodName);
        $returnType = $reflectionMethod->getReturnType();
        if ($returnType === null) {
            throw new LogicException(sprintf(
                'Retrofit: All service methods must contain a return type. None found for %s::%s()',
                $reflectionMethod->getDeclaringClass()->name,
                $reflectionMethod->name
            ));
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $returnTypeToken = new TypeToken($returnType->getName());

        $serviceMethodBuilder->setBaseUrl($this->baseUrl);
        $serviceMethodBuilder->setCallAdapter($this->callAdapterProvider->get($returnTypeToken));

        foreach ($annotations as $annotationArray) {
            if (!\is_array($annotationArray)) {
                $annotationArray = [$annotationArray];
            }

            foreach ($annotationArray as $annotation) {
                try {
                    $this->annotationProcessor->process(
                        $annotation,
                        $serviceMethodBuilder,
                        $this->converterProvider,
                        $reflectionMethod
                    );
                } catch (LogicException $exception) {
                    throw new LogicException(
                        $exception->getMessage() .
                        sprintf(
                            ' for %s::%s()',
                            $reflectionMethod->getDeclaringClass()->name,
                            $reflectionMethod->name
                        )
                    );
                }
            }
        }

        $this->applyConverters($annotations, $serviceMethodBuilder);

        return $serviceMethodBuilder->build();
    }

    /**
     * @param AnnotationCollection $annotations
     * @param DefaultServiceMethodBuilder $builder
     * @throws \LogicException
     */
    private function applyConverters(AnnotationCollection $annotations, DefaultServiceMethodBuilder $builder): void
    {
        $responseBody = $annotations->get(Annot\ResponseBody::class);
        if ($responseBody !== null) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $builder->setResponseBodyConverter(
                $this->converterProvider->getResponseBodyConverter(new TypeToken($responseBody->getValue()))
            );
        } else {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $builder->setResponseBodyConverter(
                $this->converterProvider->getResponseBodyConverter(new TypeToken(StreamInterface::class))
            );
        }

        $errorBody = $annotations->get(Annot\ErrorBody::class);
        if ($errorBody !== null) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $builder->setErrorBodyConverter(
                $this->converterProvider->getResponseBodyConverter(new TypeToken($errorBody->getValue()))
            );
        } else {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $builder->setErrorBodyConverter(
                $this->converterProvider->getResponseBodyConverter(new TypeToken(StreamInterface::class))
            );
        }
    }
}

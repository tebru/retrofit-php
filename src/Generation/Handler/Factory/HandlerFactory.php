<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Handler\Factory;

use Tebru\Dynamo\Collection\AnnotationCollection;
use Tebru\Dynamo\Model\MethodModel;
use Tebru\Retrofit\Generation\Builder\MethodBodyBuilder;
use Tebru\Retrofit\Generation\Handler\BaseUrlHandler;
use Tebru\Retrofit\Generation\Handler\RequestBodyHandler;
use Tebru\Retrofit\Generation\Handler\RequestHeaderHandler;
use Tebru\Retrofit\Generation\Handler\RequestUrlHandler;
use Tebru\Retrofit\Generation\Handler\ReturnHandler;
use Tebru\Retrofit\Generation\Handler\SerializationContextHandler;

/**
 * Class HandlerFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class HandlerFactory
{
    /**
     * Create base url handler
     *
     * @param MethodModel $methodModel
     * @param MethodBodyBuilder $methodBodyBuilder
     * @param AnnotationCollection $annotationCollection
     * @return BaseUrlHandler
     */
    public function baseUrl(MethodModel $methodModel, MethodBodyBuilder $methodBodyBuilder, AnnotationCollection $annotationCollection)
    {
        return new BaseUrlHandler($methodModel, $methodBodyBuilder, $annotationCollection);
    }

    /**
     * Create request body handler
     *
     * @param MethodModel $methodModel
     * @param MethodBodyBuilder $methodBodyBuilder
     * @param AnnotationCollection $annotationCollection
     * @return RequestBodyHandler
     */
    public function requestBody(MethodModel $methodModel, MethodBodyBuilder $methodBodyBuilder, AnnotationCollection $annotationCollection)
    {
        return new RequestBodyHandler($methodModel, $methodBodyBuilder, $annotationCollection);
    }

    /**
     * Create request header handler
     *
     * @param MethodModel $methodModel
     * @param MethodBodyBuilder $methodBodyBuilder
     * @param AnnotationCollection $annotationCollection
     * @return RequestHeaderHandler
     */
    public function requestHeader(MethodModel $methodModel, MethodBodyBuilder $methodBodyBuilder, AnnotationCollection $annotationCollection)
    {
        return new RequestHeaderHandler($methodModel, $methodBodyBuilder, $annotationCollection);
    }

    /**
     * Create request url handler
     *
     * @param MethodModel $methodModel
     * @param MethodBodyBuilder $methodBodyBuilder
     * @param AnnotationCollection $annotationCollection
     * @return RequestUrlHandler
     */
    public function requestUrl(MethodModel $methodModel, MethodBodyBuilder $methodBodyBuilder, AnnotationCollection $annotationCollection)
    {
        return new RequestUrlHandler($methodModel, $methodBodyBuilder, $annotationCollection);
    }

    /**
     * Create return handler
     *
     * @param MethodModel $methodModel
     * @param MethodBodyBuilder $methodBodyBuilder
     * @param AnnotationCollection $annotationCollection
     * @return ReturnHandler
     */
    public function returns(MethodModel $methodModel, MethodBodyBuilder $methodBodyBuilder, AnnotationCollection $annotationCollection)
    {
        return new ReturnHandler($methodModel, $methodBodyBuilder, $annotationCollection);
    }

    /**
     * Create serialization context handler
     *
     * @param MethodModel $methodModel
     * @param MethodBodyBuilder $methodBodyBuilder
     * @param AnnotationCollection $annotationCollection
     * @return SerializationContextHandler
     */
    public function serializationContext(MethodModel $methodModel, MethodBodyBuilder $methodBodyBuilder, AnnotationCollection $annotationCollection)
    {
        return new SerializationContextHandler($methodModel, $methodBodyBuilder, $annotationCollection);
    }
}

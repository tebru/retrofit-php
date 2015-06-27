<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Factory;

use PHPUnit_Framework_TestCase;
use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\DELETE;
use Tebru\Retrofit\Annotation\GET;
use Tebru\Retrofit\Annotation\HEAD;
use Tebru\Retrofit\Annotation\Header;
use Tebru\Retrofit\Annotation\Headers;
use Tebru\Retrofit\Annotation\JsonBody;
use Tebru\Retrofit\Annotation\OPTIONS;
use Tebru\Retrofit\Annotation\Part;
use Tebru\Retrofit\Annotation\PATCH;
use Tebru\Retrofit\Annotation\POST;
use Tebru\Retrofit\Annotation\PUT;
use Tebru\Retrofit\Annotation\Query;
use Tebru\Retrofit\Annotation\QueryMap;
use Tebru\Retrofit\Annotation\Returns;
use Tebru\Retrofit\Annotation\Serializer\DeserializationContext;
use Tebru\Retrofit\Annotation\Serializer\SerializationContext;
use Tebru\Retrofit\Annotation\Url;
use Tebru\Retrofit\Factory\AnnotationHandlerFactory;
use Tebru\Retrofit\Handler\BodyHandler;
use Tebru\Retrofit\Handler\HeaderHandler;
use Tebru\Retrofit\Handler\HeadersHandler;
use Tebru\Retrofit\Handler\HttpRequestHandler;
use Tebru\Retrofit\Handler\JsonBodyHandler;
use Tebru\Retrofit\Handler\PartHandler;
use Tebru\Retrofit\Handler\QueryHandler;
use Tebru\Retrofit\Handler\QueryMapHandler;
use Tebru\Retrofit\Handler\ReturnsHandler;
use Tebru\Retrofit\Handler\Serializer\DeserializationContextHandler;
use Tebru\Retrofit\Handler\Serializer\SerializationContextHandler;
use Tebru\Retrofit\Handler\UrlHandler;

/**
 * Class AnnotationHandlerFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class AnnotationHandlerFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideHttpRequestAnnotations
     */
    public function testHttpRequestAnnotations($class, $expectedClass)
    {
        $factory = new AnnotationHandlerFactory();

        // instantiate with value that works for all annotations
        $handler = $factory->make(new $class(['value' => 'Foo: bar']));

        $this->assertTrue($handler instanceof $expectedClass);
    }

    /**
     * @expectedException \Tebru\Retrofit\Exception\UnknownAnnotationHandlerException
     */
    public function testFactoryThrowsException()
    {
        $factory = new AnnotationHandlerFactory();
        $factory->make(null);
    }

    public function provideHttpRequestAnnotations()
    {
        return [
            [GET::class, HttpRequestHandler::class],
            [POST::class, HttpRequestHandler::class],
            [PUT::class, HttpRequestHandler::class],
            [PATCH::class, HttpRequestHandler::class],
            [DELETE::class, HttpRequestHandler::class],
            [HEAD::class, HttpRequestHandler::class],
            [OPTIONS::class, HttpRequestHandler::class],
            [Url::class, UrlHandler::class],
            [Query::class, QueryHandler::class],
            [QueryMap::class, QueryMapHandler::class],
            [Part::class, PartHandler::class],
            [Header::class, HeaderHandler::class],
            [Headers::class, HeadersHandler::class],
            [Body::class, BodyHandler::class],
            [JsonBody::class, JsonBodyHandler::class],
            [Returns::class, ReturnsHandler::class],
            [SerializationContext::class, SerializationContextHandler::class],
            [DeserializationContext::class, DeserializationContextHandler::class],
        ];
    }
}

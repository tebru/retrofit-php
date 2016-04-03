<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation;

use PhpParser\Node\Expr\AssignOp\Mul;
use Tebru\Dynamo\Annotation\DynamoAnnotation;
use Tebru\Retrofit\Annotation\BaseUrl;
use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\DELETE;
use Tebru\Retrofit\Annotation\FormUrlEncoded;
use Tebru\Retrofit\Annotation\GET;
use Tebru\Retrofit\Annotation\HEAD;
use Tebru\Retrofit\Annotation\Header;
use Tebru\Retrofit\Annotation\Headers;
use Tebru\Retrofit\Annotation\JsonBody;
use Tebru\Retrofit\Annotation\Multipart;
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
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class DynamoAnnotationTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class DynamoAnnotationTest extends MockeryTestCase
{
    /**
     * @dataProvider annotationDataProvider
     */
    public function testGetNameAndMultiple($annotationClass, $name, $allowMultiple)
    {
        /** @var DynamoAnnotation $annotation */
        $annotation = new $annotationClass(['value' => '/path:forheader']);

        $this->assertSame($name, $annotation->getName());
        $this->assertSame($allowMultiple, $annotation->allowMultiple());
    }

    public function annotationDataProvider()
    {
        return [
            [DELETE::class, DELETE::NAME, false],
            [GET::class, GET::NAME, false],
            [HEAD::class, HEAD::NAME, false],
            [OPTIONS::class, OPTIONS::NAME, false],
            [PATCH::class, PATCH::NAME, false],
            [POST::class, POST::NAME, false],
            [PUT::class, PUT::NAME, false],
            [BaseUrl::class, BaseUrl::NAME, false],
            [Body::class, Body::NAME, false],
            [Header::class, Header::NAME, true],
            [Headers::class, Headers::NAME, false],
            [JsonBody::class, JsonBody::NAME, false],
            [FormUrlEncoded::class, FormUrlEncoded::NAME, false],
            [Multipart::class, Multipart::NAME, false],
            [Part::class, Part::NAME, true],
            [Query::class, Query::NAME, true],
            [QueryMap::class, QueryMap::NAME, false],
            [Returns::class, Returns::NAME, false],
            [SerializationContext::class, SerializationContext::NAME, false],
            [DeserializationContext::class, DeserializationContext::NAME, false],
        ];
    }
}

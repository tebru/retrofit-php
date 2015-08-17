<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Builder;

use Tebru\Retrofit\Generation\Builder\MethodBodyBuilder;
use Tebru\Retrofit\Test\Mock\MockUser;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class MethodBodyBuilderTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class MethodBodyBuilderTest extends MockeryTestCase
{
    public function testCanBuildGetRequest()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('GET');
        $builder->setBaseUrl('http://example.com');
        $builder->setUri('/path');
        $builder->setQueries(['foo' => 'bar']);
        $builder->setQueryMap('$map');
        $builder->setReturnType('array');

        $response = $builder->build();
        $expected = '$queryString = urldecode(http_build_query(["foo" => "bar"] + $map));$requestUrl = http://example.com . "/path?" . $queryString;$headers = [];$body = null;$request = $this->client->createRequest("GET", $requestUrl, $headers, $body);$response = $this->client->send($request);return json_decode($response->getBody(true), true);';

        $this->assertSame($expected, $response);
    }

    public function testQueryMapNoQueries()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('GET');
        $builder->setBaseUrl('http://example.com');
        $builder->setUri('/path');
        $builder->setQueryMap('$map');
        $builder->setReturnType('array');

        $response = $builder->build();
        $expected = '$queryString = urldecode(http_build_query($map));$requestUrl = http://example.com . "/path?" . $queryString;$headers = [];$body = null;$request = $this->client->createRequest("GET", $requestUrl, $headers, $body);$response = $this->client->send($request);return json_decode($response->getBody(true), true);';

        $this->assertSame($expected, $response);
    }

    public function testNoQueryMap()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('GET');
        $builder->setBaseUrl('http://example.com');
        $builder->setUri('/path');
        $builder->setQueries(['foo' => 'bar']);
        $builder->setReturnType('array');

        $response = $builder->build();
        $expected = '$requestUrl = http://example.com . "/path" . "?foo=bar";$headers = [];$body = null;$request = $this->client->createRequest("GET", $requestUrl, $headers, $body);$response = $this->client->send($request);return json_decode($response->getBody(true), true);';

        $this->assertSame($expected, $response);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Request annotation not found (e.g. @GET, @POST)
     */
    public function testNoRequestMethod()
    {
        $builder = new MethodBodyBuilder();
        $builder->build();
    }

    public function testSimpleBody()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setBaseUrl('http://example.com');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $builder->setBodyIsObject(false);
        $builder->setReturnType('raw');

        $response = $builder->build();
        $expected = '$requestUrl = http://example.com . "/path";$headers = [];$body = $body;$request = $this->client->createRequest("POST", $requestUrl, $headers, $body);$response = $this->client->send($request);return $response->getBody(true);';

        $this->assertSame($expected, $response);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Cannot have both @Body and @Part annotations
     */
    public function testBodyAndPartsThrowsException()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $builder->setBodyParts(['foo' => 'bar']);
        $builder->build();
    }

    public function testCanBuildPostRequest()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setBaseUrl('http://example.com');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $builder->setBodyIsObject(true);
        $builder->setHeaders(['Content-Type' => 'application/json']);
        $context = ['groups' => ['test' => 'group'], 'version' => 1, 'serializeNull' => true, 'enableMaxDepthChecks' => true, 'attributes' => ['foo' => 'bar']];
        $builder->setSerializationContext($context);
        $context['depth'] = 2;
        $builder->setDeserializationContext($context);
        $builder->setReturnType(MockUser::class);

        $response = $builder->build();
        $expected = '$requestUrl = http://example.com . "/path";$headers = ["Content-Type" => "application/json"];$context = \Jms\Serializer\SerializationContext::create();$context->setGroups(["test" => "group"]);$context->setVersion(1);$context->setSerializeNull(1);$context->enableMaxDepthChecks(1);$context->setAttribute("foo", "bar");$body = $this->serializer->serialize($body, "json", $context);$request = $this->client->createRequest("POST", $requestUrl, $headers, $body);$response = $this->client->send($request);$context = \JMS\Serializer\DeserializationContext::create();$context->setGroups(["test" => "group"]);$context->setVersion(1);$context->setSerializeNull(1);$context->enableMaxDepthChecks(1);$context->setAttribute("foo", "bar");while ($context->getDepth() > 2) { $context->decreaseDepth(); }while ($context->getDepth() < 2) { $context->increaseDepth(); }return $this->serializer->deserialize($response->getBody(true), "Tebru\Retrofit\Test\Mock\MockUser", "json", $context);';

        $this->assertSame($expected, $response);
    }

    public function testCanBuildPostRequestParts()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setBaseUrl('http://example.com');
        $builder->setUri('/path');
        $builder->setBodyParts(['foo' => 'bar']);
        $builder->setReturnType(MockUser::class);

        $response = $builder->build();
        $expected = '$requestUrl = http://example.com . "/path";$headers = [];$body = ["foo" => "bar"];$request = $this->client->createRequest("POST", $requestUrl, $headers, $body);$response = $this->client->send($request);$context = \JMS\Serializer\DeserializationContext::create();return $this->serializer->deserialize($response->getBody(true), "Tebru\Retrofit\Test\Mock\MockUser", "json", $context);';

        $this->assertSame($expected, $response);
    }
}

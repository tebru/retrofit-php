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
        $expected = '$queryString = urldecode(http_build_query(["foo" => "bar"] + $map));$requestUrl = http://example.com . "/path?" . $queryString;$headers = [];$body = null;$this->eventDispatcher->dispatch("retrofit.beforeSend", new \Tebru\Retrofit\Event\BeforeSendEvent("GET", $requestUrl, $headers, $body));try {$response = $this->client->send("GET", $requestUrl, $headers, $body);} catch (\Exception $exception) {$this->eventDispatcher->dispatch("retrofit.apiException", new \Tebru\Retrofit\Event\ApiExceptionEvent($exception));throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);}$this->eventDispatcher->dispatch("retrofit.afterSend", new \Tebru\Retrofit\Event\AfterSendEvent($response));return json_decode($response->getBody(), true);';

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
        $expected = '$queryString = urldecode(http_build_query($map));$requestUrl = http://example.com . "/path?" . $queryString;$headers = [];$body = null;$this->eventDispatcher->dispatch("retrofit.beforeSend", new \Tebru\Retrofit\Event\BeforeSendEvent("GET", $requestUrl, $headers, $body));try {$response = $this->client->send("GET", $requestUrl, $headers, $body);} catch (\Exception $exception) {$this->eventDispatcher->dispatch("retrofit.apiException", new \Tebru\Retrofit\Event\ApiExceptionEvent($exception));throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);}$this->eventDispatcher->dispatch("retrofit.afterSend", new \Tebru\Retrofit\Event\AfterSendEvent($response));return json_decode($response->getBody(), true);';

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
        $expected = '$requestUrl = http://example.com . "/path" . "?foo=bar";$headers = [];$body = null;$this->eventDispatcher->dispatch("retrofit.beforeSend", new \Tebru\Retrofit\Event\BeforeSendEvent("GET", $requestUrl, $headers, $body));try {$response = $this->client->send("GET", $requestUrl, $headers, $body);} catch (\Exception $exception) {$this->eventDispatcher->dispatch("retrofit.apiException", new \Tebru\Retrofit\Event\ApiExceptionEvent($exception));throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);}$this->eventDispatcher->dispatch("retrofit.afterSend", new \Tebru\Retrofit\Event\AfterSendEvent($response));return json_decode($response->getBody(), true);';

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
        $builder->setBodyIsArray(true);
        $builder->setReturnType('raw');

        $response = $builder->build();
        $expected = '$requestUrl = http://example.com . "/path";$headers = [];$body = http_build_query($body);$this->eventDispatcher->dispatch("retrofit.beforeSend", new \Tebru\Retrofit\Event\BeforeSendEvent("POST", $requestUrl, $headers, $body));try {$response = $this->client->send("POST", $requestUrl, $headers, $body);} catch (\Exception $exception) {$this->eventDispatcher->dispatch("retrofit.apiException", new \Tebru\Retrofit\Event\ApiExceptionEvent($exception));throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);}$this->eventDispatcher->dispatch("retrofit.afterSend", new \Tebru\Retrofit\Event\AfterSendEvent($response));return $response->getBody();';

        $this->assertSame($expected, $response);
    }

    public function testJsonBody()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setBaseUrl('http://example.com');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $builder->setBodyIsObject(false);
        $builder->setBodyIsArray(true);
        $builder->setJsonEncode(true);
        $builder->setReturnType('raw');

        $response = $builder->build();
        $expected = '$requestUrl = http://example.com . "/path";$headers = [];$body = json_encode($body);$this->eventDispatcher->dispatch("retrofit.beforeSend", new \Tebru\Retrofit\Event\BeforeSendEvent("POST", $requestUrl, $headers, $body));try {$response = $this->client->send("POST", $requestUrl, $headers, $body);} catch (\Exception $exception) {$this->eventDispatcher->dispatch("retrofit.apiException", new \Tebru\Retrofit\Event\ApiExceptionEvent($exception));throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);}$this->eventDispatcher->dispatch("retrofit.afterSend", new \Tebru\Retrofit\Event\AfterSendEvent($response));return $response->getBody();';

        $this->assertSame($expected, $response);
    }

    public function testSimpleBodyString()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setBaseUrl('http://example.com');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $builder->setBodyIsObject(false);
        $builder->setBodyIsArray(false);
        $builder->setReturnType('raw');

        $response = $builder->build();
        $expected = '$requestUrl = http://example.com . "/path";$headers = [];$body = $body;$this->eventDispatcher->dispatch("retrofit.beforeSend", new \Tebru\Retrofit\Event\BeforeSendEvent("POST", $requestUrl, $headers, $body));try {$response = $this->client->send("POST", $requestUrl, $headers, $body);} catch (\Exception $exception) {$this->eventDispatcher->dispatch("retrofit.apiException", new \Tebru\Retrofit\Event\ApiExceptionEvent($exception));throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);}$this->eventDispatcher->dispatch("retrofit.afterSend", new \Tebru\Retrofit\Event\AfterSendEvent($response));return $response->getBody();';

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
        $builder->setJsonEncode(true);
        $builder->setHeaders(['Content-Type' => 'application/json']);
        $context = ['groups' => ['test' => 'group'], 'version' => 1, 'serializeNull' => true, 'enableMaxDepthChecks' => true, 'attributes' => ['foo' => 'bar']];
        $builder->setSerializationContext($context);
        $context['depth'] = 2;
        $builder->setDeserializationContext($context);
        $builder->setReturnType(MockUser::class);

        $response = $builder->build();
        $expected = '$requestUrl = http://example.com . "/path";$headers = ["Content-Type" => "application/json"];$context = \JMS\Serializer\SerializationContext::create();$context->setGroups(["test" => "group"]);$context->setVersion(1);$context->setSerializeNull(1);$context->enableMaxDepthChecks();$context->setAttribute("foo", "bar");$body = $this->serializer->serialize($body, "json", $context);$this->eventDispatcher->dispatch("retrofit.beforeSend", new \Tebru\Retrofit\Event\BeforeSendEvent("POST", $requestUrl, $headers, $body));try {$response = $this->client->send("POST", $requestUrl, $headers, $body);} catch (\Exception $exception) {$this->eventDispatcher->dispatch("retrofit.apiException", new \Tebru\Retrofit\Event\ApiExceptionEvent($exception));throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);}$this->eventDispatcher->dispatch("retrofit.afterSend", new \Tebru\Retrofit\Event\AfterSendEvent($response));$context = \JMS\Serializer\DeserializationContext::create();$context->setGroups(["test" => "group"]);$context->setVersion(1);$context->setSerializeNull(1);$context->enableMaxDepthChecks();$context->setAttribute("foo", "bar");while ($context->getDepth() > 2) { $context->decreaseDepth(); }while ($context->getDepth() < 2) { $context->increaseDepth(); }return $this->serializer->deserialize($response->getBody(), "Tebru\Retrofit\Test\Mock\MockUser", "json", $context);';

        $this->assertSame($expected, $response);
    }

    public function testCanBuildPostRequestParts()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setBaseUrl('http://example.com');
        $builder->setUri('/path');
        $builder->setBodyParts(['foo' => 'bar']);
        $context['serializeNull'] = true;
        $builder->setDeserializationContext($context);
        $builder->setReturnType(MockUser::class);

        $response = $builder->build();
        $expected = '$requestUrl = http://example.com . "/path";$headers = [];$body = http_build_query(["foo" => "bar"]);$this->eventDispatcher->dispatch("retrofit.beforeSend", new \Tebru\Retrofit\Event\BeforeSendEvent("POST", $requestUrl, $headers, $body));try {$response = $this->client->send("POST", $requestUrl, $headers, $body);} catch (\Exception $exception) {$this->eventDispatcher->dispatch("retrofit.apiException", new \Tebru\Retrofit\Event\ApiExceptionEvent($exception));throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);}$this->eventDispatcher->dispatch("retrofit.afterSend", new \Tebru\Retrofit\Event\AfterSendEvent($response));$context = \JMS\Serializer\DeserializationContext::create();$context->setSerializeNull(1);return $this->serializer->deserialize($response->getBody(), "Tebru\Retrofit\Test\Mock\MockUser", "json", $context);';

        $this->assertSame($expected, $response);
    }

    public function testBodyObjectFormEncoded()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setBaseUrl('http://example.com');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $builder->setBodyIsObject(true);
        $builder->setReturnType(MockUser::class);

        $response = $builder->build();
        $expected = '$requestUrl = http://example.com . "/path";$headers = [];$body = $this->serializer->serialize($body, "json");$body = json_decode($body, true);$body = \Tebru\Retrofit\Generation\Manipulator\BodyManipulator::boolToString($body);$body = http_build_query($body);$this->eventDispatcher->dispatch("retrofit.beforeSend", new \Tebru\Retrofit\Event\BeforeSendEvent("POST", $requestUrl, $headers, $body));try {$response = $this->client->send("POST", $requestUrl, $headers, $body);} catch (\Exception $exception) {$this->eventDispatcher->dispatch("retrofit.apiException", new \Tebru\Retrofit\Event\ApiExceptionEvent($exception));throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);}$this->eventDispatcher->dispatch("retrofit.afterSend", new \Tebru\Retrofit\Event\AfterSendEvent($response));return $this->serializer->deserialize($response->getBody(), "Tebru\Retrofit\Test\Mock\MockUser", "json");';

        $this->assertSame($expected, $response);
    }

    public function testBodyOptional()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setBaseUrl('http://example.com');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $builder->setBodyIsObject(true);
        $builder->setBodyIsOptional(true);
        $builder->setBodyDefaultValue('null');
        $builder->setReturnType(MockUser::class);

        $response = $builder->build();
        $expected = '$requestUrl = http://example.com . "/path";$headers = [];if (null !== $body) {$body = $this->serializer->serialize($body, "json");$body = json_decode($body, true);$body = \Tebru\Retrofit\Generation\Manipulator\BodyManipulator::boolToString($body);$body = http_build_query($body);} else { $body = null; }$this->eventDispatcher->dispatch("retrofit.beforeSend", new \Tebru\Retrofit\Event\BeforeSendEvent("POST", $requestUrl, $headers, $body));try {$response = $this->client->send("POST", $requestUrl, $headers, $body);} catch (\Exception $exception) {$this->eventDispatcher->dispatch("retrofit.apiException", new \Tebru\Retrofit\Event\ApiExceptionEvent($exception));throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);}$this->eventDispatcher->dispatch("retrofit.afterSend", new \Tebru\Retrofit\Event\AfterSendEvent($response));return $this->serializer->deserialize($response->getBody(), "Tebru\Retrofit\Test\Mock\MockUser", "json");';

        $this->assertSame($expected, $response);
    }
}

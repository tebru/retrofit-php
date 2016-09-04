<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Provider;

use Tebru\Dynamo\Collection\AnnotationCollection;
use Tebru\Dynamo\Model\ClassModel;
use Tebru\Dynamo\Model\MethodModel;
use Tebru\Dynamo\Model\ParameterModel;
use Tebru\Retrofit\Annotation\BaseUrl;
use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\FormUrlEncoded;
use Tebru\Retrofit\Annotation\GET;
use Tebru\Retrofit\Annotation\Header;
use Tebru\Retrofit\Annotation\Headers;
use Tebru\Retrofit\Annotation\JsonBody;
use Tebru\Retrofit\Annotation\Multipart;
use Tebru\Retrofit\Annotation\Part;
use Tebru\Retrofit\Annotation\Query;
use Tebru\Retrofit\Annotation\QueryMap;
use Tebru\Retrofit\Annotation\ResponseType;
use Tebru\Retrofit\Annotation\Returns;
use Tebru\Retrofit\Annotation\Serializer\DeserializerContext;
use Tebru\Retrofit\Annotation\Serializer\SerializerContext;
use Tebru\Retrofit\Generation\Provider\AnnotationProvider;
use Tebru\Retrofit\Http\Callback;
use Tebru\Retrofit\Test\Mock\Api\MockApiUser;
use Tebru\Retrofit\Test\Mock\Api\MockApiUserSerializable;
use Tebru\Retrofit\Test\Mock\ApiClient;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class AnnotationProviderTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class AnnotationProviderTest extends MockeryTestCase
{
    public function testGetBaseUrl()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new BaseUrl(['value' => 'baseUrl']));

        $provider = new AnnotationProvider($collection, $method);
        $baseUrl = $provider->getBaseUrl();

        $this->assertSame('$baseUrl', $baseUrl);
    }

    public function testGetBaseUrlNull()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $baseUrl = $provider->getBaseUrl();

        $this->assertNull($baseUrl);
    }

    public function testGetRequestMethod()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new GET(['value' => '/get']));

        $provider = new AnnotationProvider($collection, $method);
        $requestMethod = $provider->getRequestMethod();

        $this->assertSame('get', $requestMethod);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Request annotation not found (e.g. @GET, @POST)
     */
    public function testGetRequestMethodThrowsException()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $provider->getRequestMethod();
    }

    public function testGetRequestUri()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new GET(['value' => '/get']));

        $provider = new AnnotationProvider($collection, $method);
        $requestUri = $provider->getRequestUri();

        $this->assertSame('/get', $requestUri);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Request annotation not found (e.g. @GET, @POST)
     */
    public function testGetRequestUriThrowsException()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $provider->getRequestUri();
    }

    public function testGetQueries()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new GET(['value' => '/get?limit=10&page=2']));

        $provider = new AnnotationProvider($collection, $method);
        $queries = $provider->getQueries();

        $this->assertSame(['limit' => '10', 'page' => '2'], $queries);
    }

    public function testGetQueriesWithQueryAnnotation()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new GET(['value' => '/get?limit=10']));
        $collection->addAnnotation(new Query(['value' => 'page']));

        $provider = new AnnotationProvider($collection, $method);
        $queries = $provider->getQueries();

        $this->assertSame(['limit' => '10', 'page' => '$page'], $queries);
    }

    public function testGetQueriesWithoutInline()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new GET(['value' => '/get']));
        $collection->addAnnotation(new Query(['value' => 'limit']));
        $collection->addAnnotation(new Query(['value' => 'page']));

        $provider = new AnnotationProvider($collection, $method);
        $queries = $provider->getQueries();

        $this->assertSame(['limit' => '$limit', 'page' => '$page'], $queries);
    }

    public function testGetQueriesNull()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new GET(['value' => '/get']));

        $provider = new AnnotationProvider($collection, $method);
        $queries = $provider->getQueries();

        $this->assertNull($queries);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Request annotation not found (e.g. @GET, @POST)
     */
    public function testGetQueriesThrowsException()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $provider->getQueries();
    }

    public function testGetQueryMap()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new QueryMap(['value' => 'queries']));

        $provider = new AnnotationProvider($collection, $method);
        $queryMap = $provider->getQueryMap();

        $this->assertSame('$queries', $queryMap);
    }

    public function testGetQueryMapNull()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $queryMap = $provider->getQueryMap();

        $this->assertNull($queryMap);
    }

    public function testGetHeaders()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new Header(['value' => 'Content-Type', 'var' => 'contentType']));

        $provider = new AnnotationProvider($collection, $method);
        $headers = $provider->getHeaders();

        $this->assertSame(['Content-Type' => '$contentType'], $headers);
    }

    public function testGetHeadersNull()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $headers = $provider->getHeaders();

        $this->assertNull($headers);
    }

    public function testGetStaticHeaders()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new Headers(['value' => 'Content-Type: application/json']));

        $provider = new AnnotationProvider($collection, $method);
        $headers = $provider->getStaticHeaders();

        $this->assertSame(['Content-Type' => 'application/json'], $headers);
    }

    public function testGetStaticHeadersNull()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $headers = $provider->getStaticHeaders();

        $this->assertNull($headers);
    }

    public function testIsJsonEncoded()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new JsonBody());

        $provider = new AnnotationProvider($collection, $method);
        $jsonEncoded = $provider->isJsonEncoded();

        $this->assertTrue($jsonEncoded);
    }

    public function testIsJsonEncodedFalse()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $jsonEncoded = $provider->isJsonEncoded();

        $this->assertFalse($jsonEncoded);
    }

    public function testIsMultipart()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new Multipart([]));

        $provider = new AnnotationProvider($collection, $method);
        $multipart = $provider->isMultipart();

        $this->assertTrue($multipart);
    }

    public function testIsMultipartFalse()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $multipart = $provider->isMultipart();

        $this->assertFalse($multipart);
    }

    public function testIsFormUrlEncoded()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new FormUrlEncoded());

        $provider = new AnnotationProvider($collection, $method);
        $formUrlEncoded = $provider->isFormUrlEncoded();

        $this->assertTrue($formUrlEncoded);
    }

    public function testIsFormUrlEncodedDefault()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $formUrlEncoded = $provider->isFormUrlEncoded();

        $this->assertTrue($formUrlEncoded);
    }

    public function testIsNotFormUrlEncodedJsonEncoded()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new JsonBody());

        $provider = new AnnotationProvider($collection, $method);
        $formUrlEncoded = $provider->isFormUrlEncoded();

        $this->assertFalse($formUrlEncoded);
    }

    public function testIsNotFormUrlEncodedMultipart()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new Multipart([]));

        $provider = new AnnotationProvider($collection, $method);
        $formUrlEncoded = $provider->isFormUrlEncoded();

        $this->assertFalse($formUrlEncoded);
    }

    public function testGetMultipartBoundary()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new Multipart(['boundary' => 'fooboundary']));

        $provider = new AnnotationProvider($collection, $method);
        $multipartBoundary = $provider->getMultipartBoundary();

        $this->assertSame('fooboundary', $multipartBoundary);
    }

    public function testGetMultipartBoundaryNotSet()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new Multipart([]));

        $provider = new AnnotationProvider($collection, $method);
        $multipartBoundary = $provider->getMultipartBoundary();

        $this->assertRegExp('/^[\da-f]{13}$/', $multipartBoundary);
    }

    public function testHasBodyWithBodyAnnotation()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new Body(['value' => 'body']));

        $provider = new AnnotationProvider($collection, $method);
        $hasBody = $provider->hasBody();

        $this->assertTrue($hasBody);
    }

    public function testHasBodyWithPartAnnotation()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new Part(['value' => 'part']));

        $provider = new AnnotationProvider($collection, $method);
        $hasBody = $provider->hasBody();

        $this->assertTrue($hasBody);
    }

    public function testHasBodyFalse()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $hasBody = $provider->hasBody();

        $this->assertFalse($hasBody);
    }

    public function testHasBodyAnnotation()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new Body(['value' => 'body']));

        $provider = new AnnotationProvider($collection, $method);
        $hasBody = $provider->hasBodyAnnotation();

        $this->assertTrue($hasBody);
    }

    public function testHasBodyAnnotationFalse()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $hasBody = $provider->hasBodyAnnotation();

        $this->assertFalse($hasBody);
    }

    public function testGetBody()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new Body(['value' => 'body']));

        $provider = new AnnotationProvider($collection, $method);
        $body = $provider->getBody();

        $this->assertSame('$body', $body);
    }

    public function testGetBodyNull()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $body = $provider->getBody();

        $this->assertNull($body);
    }

    public function testGetBodyParts()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new Part(['value' => 'part']));

        $provider = new AnnotationProvider($collection, $method);
        $parts = $provider->getBodyParts();

        $this->assertSame(['part' => '$part'], $parts);
    }

    public function testGetBodyPartsNull()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $parts = $provider->getBodyParts();

        $this->assertNull($parts);
    }

    public function testIsBodyObject()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $parameter = new ParameterModel($method, 'body', false);
        $parameter->setTypeHint(MockApiUser::class);
        $method->addParameter($parameter);
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new Body(['value' => 'body']));

        $provider = new AnnotationProvider($collection, $method);
        $isObject = $provider->isBodyObject();

        $this->assertTrue($isObject);
    }

    public function testIsBodyObjectFalse()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $isObject = $provider->isBodyObject();

        $this->assertFalse($isObject);
    }

    public function testIsBodyArray()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $parameter = new ParameterModel($method, 'body', false);
        $parameter->setTypeHint('array');
        $method->addParameter($parameter);
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new Body(['value' => 'body']));

        $provider = new AnnotationProvider($collection, $method);
        $isArray = $provider->isBodyArray();

        $this->assertTrue($isArray);
    }

    public function testIsBodyArrayFalse()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $isArray = $provider->isBodyArray();

        $this->assertFalse($isArray);
    }

    public function testIsBodyOptional()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $parameter = new ParameterModel($method, 'body', true);
        $method->addParameter($parameter);
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new Body(['value' => 'body']));

        $provider = new AnnotationProvider($collection, $method);
        $isOptional = $provider->isBodyOptional();

        $this->assertTrue($isOptional);
    }

    public function testIsBodyOptionalFalse()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $isOptional = $provider->isBodyOptional();

        $this->assertFalse($isOptional);
    }

    public function testIsBodyJsonSerializable()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $parameter = new ParameterModel($method, 'body', false);
        $parameter->setTypeHint(MockApiUserSerializable::class);
        $method->addParameter($parameter);
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new Body(['value' => 'body']));

        $provider = new AnnotationProvider($collection, $method);
        $isObject = $provider->isBodyJsonSerializable();

        $this->assertTrue($isObject);
    }

    public function testIsBodyJsonSerializableFalse()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $parameter = new ParameterModel($method, 'body', false);
        $parameter->setTypeHint(MockApiUser::class);
        $method->addParameter($parameter);
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new Body(['value' => 'body']));

        $provider = new AnnotationProvider($collection, $method);
        $isObject = $provider->isBodyJsonSerializable();

        $this->assertFalse($isObject);
    }

    public function testIsBodyJsonSerializableNotObject()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $parameter = new ParameterModel($method, 'body', false);
        $parameter->setTypeHint('foo');
        $method->addParameter($parameter);
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new Body(['value' => 'body']));

        $provider = new AnnotationProvider($collection, $method);
        $isObject = $provider->isBodyJsonSerializable();

        $this->assertFalse($isObject);
    }

    public function testGetSerializationContext()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $serializationContext = ['value' => ['groups' => ['group'], 'version' => 1, 'serializeNull' => true, 'enableMaxDepthChecks' => true, 'foo' => 'bar']];
        $collection->addAnnotation(new SerializerContext($serializationContext));

        $expected = [
            'groups' => ['group'],
            'version' => 1,
            'serializeNull' => true,
            'enableMaxDepthChecks' => true,
            'foo' => 'bar',
        ];

        $provider = new AnnotationProvider($collection, $method);
        $returnContext = $provider->getSerializationContext();

        $this->assertSame($expected, $returnContext);
    }

    public function testGetSerializationContextNull()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $context = $provider->getSerializationContext();

        $this->assertNull($context);
    }

    public function testGetDeserializationContext()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $deserializationContext = ['value' => ['groups' => ['group'], 'version' => 1, 'serializeNull' => true, 'enableMaxDepthChecks' => true, 'depth' => 1, 'foo' => 'bar']];
        $collection->addAnnotation(new DeserializerContext($deserializationContext));

        $expected = [
            'groups' => ['group'],
            'version' => 1,
            'serializeNull' => true,
            'enableMaxDepthChecks' => true,
            'depth' => 1,
            'foo' => 'bar',
        ];

        $provider = new AnnotationProvider($collection, $method);
        $returnContext = $provider->getDeserializationContext();

        $this->assertSame($expected, $returnContext);
    }

    public function testGetDeserializationContextNull()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $context = $provider->getDeserializationContext();

        $this->assertNull($context);
    }

    public function testGetReturnType()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new Returns(['value' => 'array']));

        $provider = new AnnotationProvider($collection, $method);
        $returnType = $provider->getReturnType();

        $this->assertSame('array', $returnType);
    }

    public function testGetReturnTypeNull()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $returnType = $provider->getReturnType();

        $this->assertNull($returnType);
    }

    public function testGetResponseType()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();
        $collection->addAnnotation(new ResponseType(['value' => 'array']));

        $provider = new AnnotationProvider($collection, $method);
        $returnType = $provider->getResponseType();

        $this->assertSame('array', $returnType);
    }

    public function testGetResponseTypeNull()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $returnType = $provider->getResponseType();

        $this->assertNull($returnType);
    }

    public function testGetCallback()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', ApiClient::class), 'fooMethod');
        $parameter = new ParameterModel($method, 'callback', false);
        $parameter->setTypeHint('\\' . Callback::class);
        $method->addParameter($parameter);
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $callback = $provider->getCallback();

        $this->assertSame('$callback', $callback);
    }

    public function testGetCallbackNull()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $callback = $provider->getCallback();

        $this->assertNull($callback);
    }

    /**
     * @expectedException \Tebru\Retrofit\Exception\RetrofitException
     * @expectedExceptionMessage Interfaces using async methods must implement the "AsyncAware" class
     */
    public function testGetCallbackThrowsException()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', MockApiUser::class), 'fooMethod');
        $parameter = new ParameterModel($method, 'callback', false);
        $parameter->setTypeHint('\\' . Callback::class);
        $method->addParameter($parameter);
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $provider->getCallback();
    }

    public function testIsCallbackOptional()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', ApiClient::class), 'fooMethod');
        $parameter = new ParameterModel($method, 'callback', true);
        $parameter->setTypeHint('\\' . Callback::class);
        $method->addParameter($parameter);
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $callbackOptional = $provider->isCallbackOptional();

        $this->assertTrue($callbackOptional);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Callback does not exist
     */
    public function testIsCallbackOptionalThrowsException()
    {
        $method = new MethodModel(new ClassModel('Foo', 'FooClass', 'FooInterface'), 'fooMethod');
        $collection = new AnnotationCollection();

        $provider = new AnnotationProvider($collection, $method);
        $callbackOptional = $provider->isCallbackOptional();

        $this->assertTrue($callbackOptional);
    }
}

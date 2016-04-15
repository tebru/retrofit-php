<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Provider;

use Mockery;
use OutOfBoundsException;
use Tebru\Dynamo\Collection\AnnotationCollection;
use Tebru\Dynamo\Model\ClassModel;
use Tebru\Dynamo\Model\MethodModel;
use Tebru\Dynamo\Model\ParameterModel;
use Tebru\Retrofit\Annotation\BaseUrl;
use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\FormUrlEncoded;
use Tebru\Retrofit\Annotation\Header;
use Tebru\Retrofit\Annotation\Headers;
use Tebru\Retrofit\Annotation\HttpRequest;
use Tebru\Retrofit\Annotation\JsonBody;
use Tebru\Retrofit\Annotation\Multipart;
use Tebru\Retrofit\Annotation\Part;
use Tebru\Retrofit\Annotation\Query;
use Tebru\Retrofit\Annotation\QueryMap;
use Tebru\Retrofit\Annotation\ResponseType;
use Tebru\Retrofit\Annotation\Returns;
use Tebru\Retrofit\Annotation\Serializer\DeserializationContext;
use Tebru\Retrofit\Annotation\Serializer\SerializationContext;
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
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(BaseUrl::class);

        $collection->shouldReceive('exists')->times(1)->with(BaseUrl::NAME)->andReturn(true);
        $collection->shouldReceive('get')->times(1)->with(BaseUrl::NAME)->andReturn($annotation);
        $annotation->shouldReceive('getVariable')->times(1)->with()->andReturn('$baseUrl');

        $provider = new AnnotationProvider($collection, $method);
        $baseUrl = $provider->getBaseUrl();

        $this->assertSame('$baseUrl', $baseUrl);
    }

    public function testGetBaseUrlNull()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(BaseUrl::NAME)->andReturn(false);

        $provider = new AnnotationProvider($collection, $method);
        $baseUrl = $provider->getBaseUrl();

        $this->assertNull($baseUrl);
    }

    public function testGetRequestMethod()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(HttpRequest::class);

        $collection->shouldReceive('get')->times(1)->with(HttpRequest::NAME)->andReturn($annotation);
        $annotation->shouldReceive('getType')->times(1)->with()->andReturn('GET');

        $provider = new AnnotationProvider($collection, $method);
        $requestMethod = $provider->getRequestMethod();

        $this->assertSame('GET', $requestMethod);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Request annotation not found (e.g. @GET, @POST)
     */
    public function testGetRequestMethodThrowsException()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('get')->times(1)->with(HttpRequest::NAME)->andThrow(new OutOfBoundsException());

        $provider = new AnnotationProvider($collection, $method);
        $provider->getRequestMethod();
    }

    public function testGetRequestUri()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(HttpRequest::class);

        $collection->shouldReceive('get')->times(1)->with(HttpRequest::NAME)->andReturn($annotation);
        $annotation->shouldReceive('getPath')->times(1)->with()->andReturn('/get');

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
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('get')->times(1)->with(HttpRequest::NAME)->andThrow(new OutOfBoundsException());

        $provider = new AnnotationProvider($collection, $method);
        $provider->getRequestUri();
    }

    public function testGetQueries()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(HttpRequest::class);

        $collection->shouldReceive('get')->times(1)->with(HttpRequest::NAME)->andReturn($annotation);
        $collection->shouldReceive('exists')->times(1)->with(Query::NAME)->andReturn(false);
        $annotation->shouldReceive('getQueries')->times(1)->with()->andReturn(['limit' => 10, 'page' => 2]);

        $provider = new AnnotationProvider($collection, $method);
        $queries = $provider->getQueries();

        $this->assertSame(['limit' => 10, 'page' => 2], $queries);
    }

    public function testGetQueriesWithQueryAnnotation()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $requestAnnotation = Mockery::mock(HttpRequest::class);
        $queryAnnotation = Mockery::mock(Query::class);

        $collection->shouldReceive('get')->times(1)->with(HttpRequest::NAME)->andReturn($requestAnnotation);
        $collection->shouldReceive('exists')->times(1)->with(Query::NAME)->andReturn(true);
        $collection->shouldReceive('get')->times(1)->with(Query::NAME)->andReturn([$queryAnnotation]);
        $requestAnnotation->shouldReceive('getQueries')->times(1)->with()->andReturn(['limit' => 10]);
        $queryAnnotation->shouldReceive('getRequestKey')->times(1)->with()->andReturn('page');
        $queryAnnotation->shouldReceive('getVariable')->times(1)->with()->andReturn('$page');


        $provider = new AnnotationProvider($collection, $method);
        $queries = $provider->getQueries();

        $this->assertSame(['limit' => 10, 'page' => '$page'], $queries);
    }

    public function testGetQueriesWithoutInline()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $requestAnnotation = Mockery::mock(HttpRequest::class);
        $queryAnnotation = Mockery::mock(Query::class);

        $collection->shouldReceive('get')->times(1)->with(HttpRequest::NAME)->andReturn($requestAnnotation);
        $collection->shouldReceive('exists')->times(1)->with(Query::NAME)->andReturn(true);
        $collection->shouldReceive('get')->times(1)->with(Query::NAME)->andReturn([$queryAnnotation, $queryAnnotation]);
        $requestAnnotation->shouldReceive('getQueries')->times(1)->with()->andReturn([]);
        $queryAnnotation->shouldReceive('getRequestKey')->times(2)->with()->andReturnValues(['limit', 'page']);
        $queryAnnotation->shouldReceive('getVariable')->times(2)->with()->andReturnValues(['$limit', '$page']);


        $provider = new AnnotationProvider($collection, $method);
        $queries = $provider->getQueries();

        $this->assertSame(['limit' => '$limit', 'page' => '$page'], $queries);
    }

    public function testGetQueriesNull()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(HttpRequest::class);

        $collection->shouldReceive('get')->times(1)->with(HttpRequest::NAME)->andReturn($annotation);
        $collection->shouldReceive('exists')->times(1)->with(Query::NAME)->andReturn(false);
        $annotation->shouldReceive('getQueries')->times(1)->with()->andReturn([]);


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
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('get')->times(1)->with(HttpRequest::NAME)->andThrow(new OutOfBoundsException());

        $provider = new AnnotationProvider($collection, $method);
        $provider->getQueries();
    }

    public function testGetQueryMap()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(QueryMap::class);

        $collection->shouldReceive('exists')->times(1)->with(QueryMap::NAME)->andReturn(true);
        $collection->shouldReceive('get')->times(1)->with(QueryMap::NAME)->andReturn($annotation);
        $annotation->shouldReceive('getVariable')->times(1)->with()->andReturn('$queries');

        $provider = new AnnotationProvider($collection, $method);
        $queryMap = $provider->getQueryMap();

        $this->assertSame('$queries', $queryMap);
    }

    public function testGetQueryMapNull()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(QueryMap::NAME)->andReturn(false);

        $provider = new AnnotationProvider($collection, $method);
        $queryMap = $provider->getQueryMap();

        $this->assertNull($queryMap);
    }

    public function testGetHeaders()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(Header::class);

        $collection->shouldReceive('exists')->times(1)->with(Header::NAME)->andReturn(true);
        $collection->shouldReceive('get')->times(1)->with(Header::NAME)->andReturn([$annotation]);
        $annotation->shouldReceive('getRequestKey')->times(1)->with()->andReturn('Content-Type');
        $annotation->shouldReceive('getVariable')->times(1)->with()->andReturn('$contentType');

        $provider = new AnnotationProvider($collection, $method);
        $headers = $provider->getHeaders();

        $this->assertSame(['Content-Type' => '$contentType'], $headers);
    }

    public function testGetHeadersNull()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Header::NAME)->andReturn(false);

        $provider = new AnnotationProvider($collection, $method);
        $headers = $provider->getHeaders();

        $this->assertNull($headers);
    }

    public function testGetStaticHeaders()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(Headers::class);

        $collection->shouldReceive('exists')->times(1)->with(Headers::NAME)->andReturn(true);
        $collection->shouldReceive('get')->times(1)->with(Headers::NAME)->andReturn($annotation);
        $annotation->shouldReceive('getHeaders')->times(1)->with()->andReturn(['Content-Type' => 'application/json']);

        $provider = new AnnotationProvider($collection, $method);
        $headers = $provider->getStaticHeaders();

        $this->assertSame(['Content-Type' => 'application/json'], $headers);
    }

    public function testGetStaticHeadersNull()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Headers::NAME)->andReturn(false);

        $provider = new AnnotationProvider($collection, $method);
        $headers = $provider->getStaticHeaders();

        $this->assertNull($headers);
    }

    public function testIsJsonEncoded()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(JsonBody::NAME)->andReturn(true);

        $provider = new AnnotationProvider($collection, $method);
        $jsonEncoded = $provider->isJsonEncoded();

        $this->assertTrue($jsonEncoded);
    }

    public function testIsJsonEncodedFalse()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(JsonBody::NAME)->andReturn(false);

        $provider = new AnnotationProvider($collection, $method);
        $jsonEncoded = $provider->isJsonEncoded();

        $this->assertFalse($jsonEncoded);
    }

    public function testIsMultipart()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Multipart::NAME)->andReturn(true);

        $provider = new AnnotationProvider($collection, $method);
        $multipart = $provider->isMultipart();

        $this->assertTrue($multipart);
    }

    public function testIsMultipartFalse()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Multipart::NAME)->andReturn(false);

        $provider = new AnnotationProvider($collection, $method);
        $multipart = $provider->isMultipart();

        $this->assertFalse($multipart);
    }

    public function testIsFormUrlEncoded()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(FormUrlEncoded::NAME)->andReturn(true);

        $provider = new AnnotationProvider($collection, $method);
        $formUrlEncoded = $provider->isFormUrlEncoded();

        $this->assertTrue($formUrlEncoded);
    }

    public function testIsFormUrlEncodedDefault()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(FormUrlEncoded::NAME)->andReturn(false);
        $collection->shouldReceive('exists')->times(1)->with(JsonBody::NAME)->andReturn(false);
        $collection->shouldReceive('exists')->times(1)->with(Multipart::NAME)->andReturn(false);

        $provider = new AnnotationProvider($collection, $method);
        $formUrlEncoded = $provider->isFormUrlEncoded();

        $this->assertTrue($formUrlEncoded);
    }

    public function testIsNotFormUrlEncodedJsonEncoded()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(FormUrlEncoded::NAME)->andReturn(false);
        $collection->shouldReceive('exists')->times(1)->with(JsonBody::NAME)->andReturn(true);
        $collection->shouldReceive('exists')->times(1)->with(Multipart::NAME)->andReturn(false);

        $provider = new AnnotationProvider($collection, $method);
        $formUrlEncoded = $provider->isFormUrlEncoded();

        $this->assertFalse($formUrlEncoded);
    }

    public function testIsNotFormUrlEncodedMultipart()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(FormUrlEncoded::NAME)->andReturn(false);
        $collection->shouldReceive('exists')->times(1)->with(Multipart::NAME)->andReturn(true);

        $provider = new AnnotationProvider($collection, $method);
        $formUrlEncoded = $provider->isFormUrlEncoded();

        $this->assertFalse($formUrlEncoded);
    }

    public function testGetMultipartBoundary()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(Multipart::class);

        $collection->shouldReceive('get')->times(1)->with(Multipart::NAME)->andReturn($annotation);
        $annotation->shouldReceive('getBoundary')->times(1)->with()->andReturn('fooboundary');

        $provider = new AnnotationProvider($collection, $method);
        $multipartBoundary = $provider->getMultipartBoundary();

        $this->assertSame('fooboundary', $multipartBoundary);
    }

    public function testGetMultipartBoundaryNotSet()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(Multipart::class);

        $collection->shouldReceive('get')->times(1)->with(Multipart::NAME)->andReturn($annotation);
        $annotation->shouldReceive('getBoundary')->times(1)->with()->andReturn(null);

        $provider = new AnnotationProvider($collection, $method);
        $multipartBoundary = $provider->getMultipartBoundary();

        $this->assertRegExp('/^[\da-f]{13}$/', $multipartBoundary);
    }

    public function testHasBodyWithBodyAnnotation()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(true);

        $provider = new AnnotationProvider($collection, $method);
        $hasBody = $provider->hasBody();

        $this->assertTrue($hasBody);
    }

    public function testHasBodyWithPartAnnotation()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(false);
        $collection->shouldReceive('exists')->times(1)->with(Part::NAME)->andReturn(true);

        $provider = new AnnotationProvider($collection, $method);
        $hasBody = $provider->hasBody();

        $this->assertTrue($hasBody);
    }

    public function testHasBodyFalse()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(false);
        $collection->shouldReceive('exists')->times(1)->with(Part::NAME)->andReturn(false);

        $provider = new AnnotationProvider($collection, $method);
        $hasBody = $provider->hasBody();

        $this->assertFalse($hasBody);
    }

    public function testHasBodyAnnotation()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(true);

        $provider = new AnnotationProvider($collection, $method);
        $hasBody = $provider->hasBodyAnnotation();

        $this->assertTrue($hasBody);
    }

    public function testHasBodyAnnotationFalse()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(false);

        $provider = new AnnotationProvider($collection, $method);
        $hasBody = $provider->hasBodyAnnotation();

        $this->assertFalse($hasBody);
    }

    public function testGetBody()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(Body::class);

        $collection->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(true);
        $collection->shouldReceive('get')->times(1)->with(Body::NAME)->andReturn($annotation);
        $annotation->shouldReceive('getVariable')->times(1)->with()->andReturn('$body');

        $provider = new AnnotationProvider($collection, $method);
        $body = $provider->getBody();

        $this->assertSame('$body', $body);
    }

    public function testGetBodyNull()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(false);

        $provider = new AnnotationProvider($collection, $method);
        $body = $provider->getBody();

        $this->assertNull($body);
    }

    public function testGetBodyParts()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(Part::class);

        $collection->shouldReceive('exists')->times(1)->with(Part::NAME)->andReturn(true);
        $collection->shouldReceive('get')->times(1)->with(Part::NAME)->andReturn([$annotation]);
        $annotation->shouldReceive('getRequestKey')->times(1)->with()->andReturn('part');
        $annotation->shouldReceive('getVariable')->times(1)->with()->andReturn('$part');

        $provider = new AnnotationProvider($collection, $method);
        $parts = $provider->getBodyParts();

        $this->assertSame(['part' => '$part'], $parts);
    }

    public function testGetBodyPartsNull()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Part::NAME)->andReturn(false);

        $provider = new AnnotationProvider($collection, $method);
        $parts = $provider->getBodyParts();

        $this->assertNull($parts);
    }

    public function testIsBodyObject()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(Body::class);
        $parameter = Mockery::mock(ParameterModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(true);
        $collection->shouldReceive('get')->times(1)->with(Body::NAME)->andReturn($annotation);
        $annotation->shouldReceive('getVariableName')->times(1)->with()->andReturn('$body');
        $method->shouldReceive('getParameter')->times(1)->with('$body')->andReturn($parameter);
        $parameter->shouldReceive('isObject')->times(1)->with()->andReturn(true);

        $provider = new AnnotationProvider($collection, $method);
        $isObject = $provider->isBodyObject();

        $this->assertTrue($isObject);
    }

    public function testIsBodyObjectFalse()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(false);

        $provider = new AnnotationProvider($collection, $method);
        $isObject = $provider->isBodyObject();

        $this->assertFalse($isObject);
    }

    public function testIsBodyArray()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(Body::class);
        $parameter = Mockery::mock(ParameterModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(true);
        $collection->shouldReceive('get')->times(1)->with(Body::NAME)->andReturn($annotation);
        $annotation->shouldReceive('getVariableName')->times(1)->with()->andReturn('$body');
        $method->shouldReceive('getParameter')->times(1)->with('$body')->andReturn($parameter);
        $parameter->shouldReceive('isArray')->times(1)->with()->andReturn(true);

        $provider = new AnnotationProvider($collection, $method);
        $isArray = $provider->isBodyArray();

        $this->assertTrue($isArray);
    }

    public function testIsBodyArrayFalse()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(false);

        $provider = new AnnotationProvider($collection, $method);
        $isArray = $provider->isBodyArray();

        $this->assertFalse($isArray);
    }

    public function testIsBodyOptional()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(Body::class);
        $parameter = Mockery::mock(ParameterModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(true);
        $collection->shouldReceive('get')->times(1)->with(Body::NAME)->andReturn($annotation);
        $annotation->shouldReceive('getVariableName')->times(1)->with()->andReturn('$body');
        $method->shouldReceive('getParameter')->times(1)->with('$body')->andReturn($parameter);
        $parameter->shouldReceive('isOptional')->times(1)->with()->andReturn(true);

        $provider = new AnnotationProvider($collection, $method);
        $isOptional = $provider->isBodyOptional();

        $this->assertTrue($isOptional);
    }

    public function testIsBodyOptionalFalse()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(false);

        $provider = new AnnotationProvider($collection, $method);
        $isOptional = $provider->isBodyOptional();

        $this->assertFalse($isOptional);
    }

    public function testIsBodyJsonSerializable()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(Body::class);
        $parameter = Mockery::mock(ParameterModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(true);
        $collection->shouldReceive('get')->times(2)->with(Body::NAME)->andReturn($annotation);
        $annotation->shouldReceive('getVariableName')->times(2)->with()->andReturn('$body');
        $method->shouldReceive('getParameter')->times(2)->with('$body')->andReturn($parameter);
        $parameter->shouldReceive('isObject')->times(1)->with()->andReturn(true);
        $parameter->shouldReceive('getTypeHint')->times(1)->with()->andReturn(MockApiUserSerializable::class);

        $provider = new AnnotationProvider($collection, $method);
        $isObject = $provider->isBodyJsonSerializable();

        $this->assertTrue($isObject);
    }

    public function testIsBodyJsonSerializableFalse()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(Body::class);
        $parameter = Mockery::mock(ParameterModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(true);
        $collection->shouldReceive('get')->times(2)->with(Body::NAME)->andReturn($annotation);
        $annotation->shouldReceive('getVariableName')->times(2)->with()->andReturn('$body');
        $method->shouldReceive('getParameter')->times(2)->with('$body')->andReturn($parameter);
        $parameter->shouldReceive('isObject')->times(1)->with()->andReturn(true);
        $parameter->shouldReceive('getTypeHint')->times(1)->with()->andReturn(MockApiUser::class);

        $provider = new AnnotationProvider($collection, $method);
        $isObject = $provider->isBodyJsonSerializable();

        $this->assertFalse($isObject);
    }

    public function testIsBodyJsonSerializableNotObject()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(false);

        $provider = new AnnotationProvider($collection, $method);
        $isObject = $provider->isBodyJsonSerializable();

        $this->assertFalse($isObject);
    }

    public function testGetSerializationContext()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(SerializationContext::class);

        $collection->shouldReceive('exists')->times(1)->with(SerializationContext::NAME)->andReturn(true);
        $collection->shouldReceive('get')->times(1)->with(SerializationContext::NAME)->andReturn($annotation);
        $annotation->shouldReceive('getGroups')->times(1)->with()->andReturn(['group']);
        $annotation->shouldReceive('getVersion')->times(1)->with()->andReturn(1);
        $annotation->shouldReceive('getSerializeNull')->times(1)->with()->andReturn(true);
        $annotation->shouldReceive('getEnableMaxDepthChecks')->times(1)->with()->andReturn(true);
        $annotation->shouldReceive('getAttributes')->times(1)->with()->andReturn([]);

        $expected = [
            'groups' => ['group'],
            'version' => 1,
            'serializeNull' => true,
            'enableMaxDepthChecks' => true,
            'attributes' => [],
        ];

        $provider = new AnnotationProvider($collection, $method);
        $context = $provider->getSerializationContext();

        $this->assertSame($expected, $context);
    }

    public function testGetSerializationContextNull()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(SerializationContext::NAME)->andReturn(false);

        $provider = new AnnotationProvider($collection, $method);
        $context = $provider->getSerializationContext();

        $this->assertNull($context);
    }

    public function testGetDeserializationContext()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(DeserializationContext::class);

        $collection->shouldReceive('exists')->times(1)->with(DeserializationContext::NAME)->andReturn(true);
        $collection->shouldReceive('get')->times(1)->with(DeserializationContext::NAME)->andReturn($annotation);
        $annotation->shouldReceive('getGroups')->times(1)->with()->andReturn(['group']);
        $annotation->shouldReceive('getVersion')->times(1)->with()->andReturn(1);
        $annotation->shouldReceive('getSerializeNull')->times(1)->with()->andReturn(true);
        $annotation->shouldReceive('getEnableMaxDepthChecks')->times(1)->with()->andReturn(true);
        $annotation->shouldReceive('getAttributes')->times(1)->with()->andReturn([]);
        $annotation->shouldReceive('getDepth')->times(1)->with()->andReturn(1);

        $expected = [
            'groups' => ['group'],
            'version' => 1,
            'serializeNull' => true,
            'enableMaxDepthChecks' => true,
            'attributes' => [],
            'depth' => 1,
        ];

        $provider = new AnnotationProvider($collection, $method);
        $context = $provider->getDeserializationContext();

        $this->assertSame($expected, $context);
    }

    public function testGetDeserializationContextNull()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(DeserializationContext::NAME)->andReturn(false);

        $provider = new AnnotationProvider($collection, $method);
        $context = $provider->getDeserializationContext();

        $this->assertNull($context);
    }

    public function testGetReturnType()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(Returns::class);

        $collection->shouldReceive('exists')->times(1)->with(Returns::NAME)->andReturn(true);
        $collection->shouldReceive('get')->times(1)->with(Returns::NAME)->andReturn($annotation);
        $annotation->shouldReceive('getReturn')->times(1)->with()->andReturn('array');

        $provider = new AnnotationProvider($collection, $method);
        $returnType = $provider->getReturnType();

        $this->assertSame('array', $returnType);
    }

    public function testGetReturnTypeNull()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(Returns::NAME)->andReturn(false);

        $provider = new AnnotationProvider($collection, $method);
        $returnType = $provider->getReturnType();

        $this->assertNull($returnType);
    }

    public function testGetResponseType()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $annotation = Mockery::mock(ResponseType::class);

        $collection->shouldReceive('exists')->times(1)->with(ResponseType::NAME)->andReturn(true);
        $collection->shouldReceive('get')->times(1)->with(ResponseType::NAME)->andReturn($annotation);
        $annotation->shouldReceive('getType')->times(1)->with()->andReturn('array');

        $provider = new AnnotationProvider($collection, $method);
        $returnType = $provider->getResponseType();

        $this->assertSame('array', $returnType);
    }

    public function testGetResponseTypeNull()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);

        $collection->shouldReceive('exists')->times(1)->with(ResponseType::NAME)->andReturn(false);

        $provider = new AnnotationProvider($collection, $method);
        $returnType = $provider->getResponseType();

        $this->assertNull($returnType);
    }

    public function testGetCallback()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $parameter = Mockery::mock(ParameterModel::class);
        $class = Mockery::mock(ClassModel::class);

        $method->shouldReceive('getParameters')->times(1)->with()->andReturn([$parameter]);
        $method->shouldReceive('getClassModel')->times(1)->with()->andReturn($class);
        $parameter->shouldReceive('getTypeHint')->times(1)->with()->andReturn('\\' . Callback::class);
        $parameter->shouldReceive('getName')->times(1)->with()->andReturn('callback');
        $class->shouldReceive('getInterface')->times(1)->with()->andReturn(ApiClient::class);

        $provider = new AnnotationProvider($collection, $method);
        $callback = $provider->getCallback();

        $this->assertSame('$callback', $callback);
    }

    public function testGetCallbackNull()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $parameter = Mockery::mock(ParameterModel::class);

        $method->shouldReceive('getParameters')->times(1)->with()->andReturn([$parameter]);
        $parameter->shouldReceive('getTypeHint')->times(1)->with()->andReturn('foo');

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
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $parameter = Mockery::mock(ParameterModel::class);
        $class = Mockery::mock(ClassModel::class);

        $method->shouldReceive('getParameters')->times(1)->with()->andReturn([$parameter]);
        $method->shouldReceive('getClassModel')->times(1)->with()->andReturn($class);
        $parameter->shouldReceive('getTypeHint')->times(1)->with()->andReturn('\\' . Callback::class);
        $class->shouldReceive('getInterface')->times(1)->with()->andReturn(MockApiUser::class);

        $provider = new AnnotationProvider($collection, $method);
        $provider->getCallback();
    }

    public function testIsCallbackOptional()
    {
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $parameter = Mockery::mock(ParameterModel::class);
        $class = Mockery::mock(ClassModel::class);

        $method->shouldReceive('getParameters')->times(1)->with()->andReturn([$parameter]);
        $method->shouldReceive('getClassModel')->times(1)->with()->andReturn($class);
        $parameter->shouldReceive('getTypeHint')->times(1)->with()->andReturn('\\' . Callback::class);
        $parameter->shouldReceive('isOptional')->times(1)->with()->andReturn(true);
        $class->shouldReceive('getInterface')->times(1)->with()->andReturn(ApiClient::class);

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
        $collection = Mockery::mock(AnnotationCollection::class);
        $method = Mockery::mock(MethodModel::class);
        $parameter = Mockery::mock(ParameterModel::class);

        $method->shouldReceive('getParameters')->times(1)->with()->andReturn([$parameter]);
        $parameter->shouldReceive('getTypeHint')->times(1)->with()->andReturn('foo');

        $provider = new AnnotationProvider($collection, $method);
        $callbackOptional = $provider->isCallbackOptional();

        $this->assertTrue($callbackOptional);
    }
}

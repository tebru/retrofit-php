<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit;

use LogicException;
use RuntimeException;
use Tebru\Retrofit\Annotation\GET;
use Tebru\Retrofit\Finder\ServiceResolver;
use Tebru\Retrofit\Http\MultipartBody;
use Tebru\Retrofit\Internal\CacheProvider;
use Tebru\Retrofit\Retrofit;
use PHPUnit\Framework\TestCase;
use Tebru\Retrofit\RetrofitBuilder;
use Tebru\Retrofit\Test\Mock\Unit\RetrofitTest\ApiClient;
use Tebru\Retrofit\Test\Mock\Unit\RetrofitTest\CacheableApiClient;
use Tebru\Retrofit\Test\Mock\Unit\RetrofitTest\DefaultParamsApiClient;
use Tebru\Retrofit\Test\Mock\Unit\RetrofitTest\InvalidSyntaxApiClient;
use Tebru\Retrofit\Test\Mock\Unit\RetrofitTest\RetrofitTestAdaptedCallMock;
use Tebru\Retrofit\Test\Mock\Unit\RetrofitTest\RetrofitTestCallAdapterFactory;
use Tebru\Retrofit\Test\Mock\Unit\RetrofitTest\RetrofitTestConverterFactory;
use Tebru\Retrofit\Test\Mock\Unit\RetrofitTest\RetrofitTestCustomAnnotation;
use Tebru\Retrofit\Test\Mock\Unit\RetrofitTest\RetrofitTestCustomAnnotationHandler;
use Tebru\Retrofit\Test\Mock\Unit\RetrofitTest\RetrofitTestHttpClient;
use Tebru\Retrofit\Test\Mock\Unit\RetrofitTest\RetrofitTestProxyFactory;
use Tebru\Retrofit\Test\Mock\Unit\RetrofitTest\RetrofitTestRequestBodyMock;
use Tebru\Retrofit\Test\Mock\Unit\RetrofitTest\RetrofitTestResponseBodyMock;

class RetrofitTest extends TestCase
{
    /**
     * @var RetrofitTestHttpClient
     */
    private $httpClient;

    /**
     * @var RetrofitBuilder
     */
    private $retrofitBuilder;

    public function setUp()
    {
        $this->httpClient = new RetrofitTestHttpClient();
        $this->retrofitBuilder = Retrofit::builder()
            ->setBaseUrl('http://example.com')
            ->setHttpClient($this->httpClient);
    }

    public function testSimple()
    {
        $retrofit = $this->retrofitBuilder->build();

        /** @var ApiClient $service */
        $service = $retrofit->create(ApiClient::class);

        $service->get()->execute();

        self::assertCount(1, $this->httpClient->requests);

        $request = $this->httpClient->requests[0];

        self::assertSame('GET', $request->getMethod());
        self::assertSame('http://example.com/', (string)$request->getUri());
        self::assertSame('', (string)$request->getBody());
    }

    public function testUri()
    {
        $retrofit = $this->retrofitBuilder->build();

        /** @var ApiClient $service */
        $service = $retrofit->create(ApiClient::class);

        $service
            ->uri(
                'https://example2.com',
                ['foo' => 'bar'],
                ['one', 2, true],
                false,
                'testpart'
            )
            ->execute();

        self::assertCount(1, $this->httpClient->requests);

        $request = $this->httpClient->requests[0];

        self::assertSame('OPTIONS', $request->getMethod());
        self::assertSame('https://example2.com/testpart?foo=bar&query[]=one&query[]=2&query[]=true&false&q=test', rawurldecode((string)$request->getUri()));
        self::assertSame('', (string)$request->getBody());
    }

    public function testHeaders()
    {
        $retrofit = $this->retrofitBuilder->build();

        /** @var ApiClient $service */
        $service = $retrofit->create(ApiClient::class);

        $service
            ->headers(
                ['X-Header[]' => ['one', 2, false]],
                [true, 3.14],
                5
            )
            ->execute();

        self::assertCount(1, $this->httpClient->requests);

        $request = $this->httpClient->requests[0];

        self::assertSame('HEAD', $request->getMethod());
        self::assertSame('http://example.com/', (string)$request->getUri());
        self::assertSame('', (string)$request->getBody());
        self::assertSame(
            [
                'Host' => ['example.com'],
                'x-foo' => ['bar'],
                'x-baz' => ['qux'],
                'x-header[]' => ['first', 'one', '2', 'false', 'true', '3.14'],
                'header2' => ['5']
            ],
            $request->getHeaders()
        );
    }

    public function testPostWithoutBody()
    {
        $retrofit = $this->retrofitBuilder->build();

        /** @var ApiClient $service */
        $service = $retrofit->create(ApiClient::class);

        $service->postWithoutBody()->execute();

        self::assertCount(1, $this->httpClient->requests);

        $request = $this->httpClient->requests[0];

        self::assertSame('POST', $request->getMethod());
        self::assertSame('http://example.com/', (string)$request->getUri());
        self::assertSame('', (string)$request->getBody());
    }

    public function testBody()
    {
        $retrofit = $this->retrofitBuilder
            ->addConverterFactory(new RetrofitTestConverterFactory())
            ->build();

        /** @var ApiClient $service */
        $service = $retrofit->create(ApiClient::class);

        $body = new RetrofitTestRequestBodyMock();
        $body->id = 1;
        $body->name = 'Nate';

        $responseBody = $service->body($body)->execute()->body();

        self::assertCount(1, $this->httpClient->requests);

        $request = $this->httpClient->requests[0];

        self::assertSame('PUT', $request->getMethod());
        self::assertSame('http://example.com/', (string)$request->getUri());
        self::assertSame('{"id":1,"name":"Nate"}', (string)$request->getBody());
        self::assertSame(['Host' => ['example.com'], 'content-type' => ['application/json']], $request->getHeaders());
        self::assertInstanceOf(RetrofitTestResponseBodyMock::class, $responseBody);
    }

    public function testField()
    {
        $retrofit = $this->retrofitBuilder->build();

        /** @var ApiClient $service */
        $service = $retrofit->create(ApiClient::class);

        $service->field(5.3, false, 'foo%28%29', ['foo' => 'bar'])->execute();

        self::assertCount(1, $this->httpClient->requests);

        $request = $this->httpClient->requests[0];

        self::assertSame('PATCH', $request->getMethod());
        self::assertSame('http://example.com/', (string)$request->getUri());
        self::assertSame('field1=5.3&field2=false&field3=foo%28%29&foo=bar', (string)$request->getBody());
        self::assertSame(['Host' => ['example.com'], 'content-type' => ['application/x-www-form-urlencoded']], $request->getHeaders());
    }

    public function testPart()
    {
        $retrofit = $this->retrofitBuilder
            ->addConverterFactory(new RetrofitTestConverterFactory())
            ->build();

        /** @var ApiClient $service */
        $service = $retrofit->create(ApiClient::class);

        $body = new RetrofitTestRequestBodyMock();
        $body->id = 1;
        $body->name = 'Nate';

        $multipartRequestBody = new RetrofitTestRequestBodyMock();
        $multipartRequestBody->id = 2;
        $multipartRequestBody->name = 'Mike';

        $multipartBody = new MultipartBody('foo', 'bar');

        $service->part($body, $multipartBody, ['baz' => $multipartRequestBody])->execute();

        self::assertCount(1, $this->httpClient->requests);

        $request = $this->httpClient->requests[0];

        self::assertSame('FOO', $request->getMethod());
        self::assertSame('http://example.com/', (string)$request->getUri());
        self::assertSame(['Host' => ['example.com'], 'content-type' => ['multipart/form-data']], $request->getHeaders());
        self::assertNotFalse(strpos(
            (string)$request->getBody(),
            'Content-Disposition: form-data; name="part1"'
        ));
        self::assertNotFalse(strpos(
            (string)$request->getBody(),
            'Content-Disposition: form-data; name="foo"'
        ));
        self::assertNotFalse(strpos(
            (string)$request->getBody(),
            'Content-Disposition: form-data; name="baz"'
        ));
        self::assertNotFalse(strpos(
            (string)$request->getBody(),
            '{"id":1,"name":"Nate"}'
        ));
        self::assertNotFalse(strpos(
            (string)$request->getBody(),
            '{"id":2,"name":"Mike"}'
        ));
    }

    public function testCallAdapter()
    {
        $retrofit = $this->retrofitBuilder
            ->addCallAdapterFactory(new RetrofitTestCallAdapterFactory())
            ->build();

        /** @var ApiClient $service */
        $service = $retrofit->create(ApiClient::class);

        $adaptedCall = $service->callAdapter();

        self::assertInstanceOf(RetrofitTestAdaptedCallMock::class, $adaptedCall);
    }

    public function testCustomProxy()
    {
        $retrofit = $this->retrofitBuilder
            ->addProxyFactory(new RetrofitTestProxyFactory())
            ->build();

        /** @var ApiClient $service */
        $service = $retrofit->create(ApiClient::class);

        $service->get()->execute();

        self::assertCount(1, $this->httpClient->requests);

        $request = $this->httpClient->requests[0];

        self::assertSame('GET', $request->getMethod());
        self::assertSame('http://example.com/', (string)$request->getUri());
        self::assertSame('', (string)$request->getBody());
    }

    public function testCustomAnnotation()
    {
        $retrofit = $this->retrofitBuilder
            ->addAnnotationHandler(RetrofitTestCustomAnnotation::class, new RetrofitTestCustomAnnotationHandler())
            ->build();

        /** @var ApiClient $service */
        $service = $retrofit->create(ApiClient::class);

        $service->customAnnotation()->execute();

        self::assertCount(1, $this->httpClient->requests);

        $request = $this->httpClient->requests[0];

        self::assertSame('DELETE', $request->getMethod());
        self::assertSame('http://example.com/', (string)$request->getUri());
        self::assertSame('', (string)$request->getBody());
        self::assertSame(['Host' => ['example.com'], 'foo' => ['bar']], $request->getHeaders());
    }

    public function testGetWithDefaults()
    {
        $retrofit = $this->retrofitBuilder->build();

        /** @var DefaultParamsApiClient $service */
        $service = $retrofit->create(DefaultParamsApiClient::class);

        $service->getWithDefaults()->execute();

        self::assertCount(1, $this->httpClient->requests);

        $request = $this->httpClient->requests[0];

        self::assertSame('GET', $request->getMethod());
        self::assertSame('http://example.com/?string=test&bool=true&int=1&float=3.2', (string)$request->getUri());
        self::assertSame('', (string)$request->getBody());
        self::assertSame(['Host' => ['example.com'], 'test' => ['value']], $request->getHeaders());
    }

    public function testGetWithSomeDefaults()
    {
        $retrofit = $this->retrofitBuilder->build();

        /** @var DefaultParamsApiClient $service */
        $service = $retrofit->create(DefaultParamsApiClient::class);

        $service->getWithDefaults('test2', false)->execute();

        self::assertCount(1, $this->httpClient->requests);

        $request = $this->httpClient->requests[0];

        self::assertSame('GET', $request->getMethod());
        self::assertSame('http://example.com/?string=test2&bool=false&int=1&float=3.2', (string)$request->getUri());
        self::assertSame('', (string)$request->getBody());
        self::assertSame(['Host' => ['example.com'], 'test' => ['value']], $request->getHeaders());
    }

    public function testGetWithNullDefaults()
    {
        $retrofit = $this->retrofitBuilder->build();

        /** @var DefaultParamsApiClient $service */
        $service = $retrofit->create(DefaultParamsApiClient::class);

        $service->getWithDefaults(null, null, null, null, null, null)->execute();

        self::assertCount(1, $this->httpClient->requests);

        $request = $this->httpClient->requests[0];

        self::assertSame('GET', $request->getMethod());
        self::assertSame('http://example.com/', (string)$request->getUri());
        self::assertSame('', (string)$request->getBody());
        self::assertSame(['Host' => ['example.com']], $request->getHeaders());
    }

    public function testCache()
    {
        $cacheDir = __DIR__.'/../cache';
        $file = $cacheDir.'/retrofit/Tebru/Retrofit/Test/Mock/Unit/RetrofitTest/CacheableApiClient.php';

        if (file_exists($file)) {
            $success = unlink($file);

            if (!$success) {
                throw new RuntimeException('Could not cleanup test');
            }
        }

        $retrofit = $this->retrofitBuilder
            ->enableCache()
            ->setCacheDir($cacheDir)
            ->build();

        /** @var CacheableApiClient $service */
        $service = $retrofit->create(CacheableApiClient::class);

        $service->get()->execute();

        self::assertFileExists($file);
        unlink($file);
    }

    public function testCustomCache()
    {
        $cache = CacheProvider::createMemoryCache();

        $retrofit = $this->retrofitBuilder
            ->setCache($cache)
            ->build();

        /** @var CacheableApiClient $service */
        $service = $retrofit->create(CacheableApiClient::class);

        $service->get()->execute();

        $annotation = new GET(['value' => '/']);
        self::assertEquals([GET::class => $annotation], $cache->get('annotationreader.TebruRetrofitTestMockUnitRetrofitTestCacheableApiClientget'));
    }

    public function testBuilderThrowsExceptionWithoutBaseUrl()
    {
        try {
            Retrofit::builder()->build();
        } catch (LogicException $exception) {
            self::assertSame('Retrofit: Base URL must be provided', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testBuilderThrowsExceptionWithoutHttpClient()
    {
        try {
            Retrofit::builder()
                ->setBaseUrl('http://example.com')
                ->build();
        } catch (LogicException $exception) {
            self::assertSame('Retrofit: Must set http client to make requests', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testBuilderThrowsExceptionWithoutCacheDir()
    {
        try {
            $this->retrofitBuilder->enableCache()->build();
        } catch (LogicException $exception) {
            self::assertSame('Retrofit: If caching is enabled, must specify cache directory', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testCreateServices()
    {
        $retrofit = $this->retrofitBuilder->build();
        $retrofit->registerServices([ApiClient::class]);

        self::assertSame(1, $retrofit->createServices());
    }

    public function testCreateAll()
    {
        $retrofit = $this->retrofitBuilder->build();

        self::assertSame(4, $retrofit->createAll(__DIR__.'/../Mock/Unit/RetrofitTest/'));
    }

    public function testCreateThrowsExceptionWithoutFactory()
    {
        $retrofit = new Retrofit(new ServiceResolver(), []);
        try {
            $retrofit->create(ApiClient::class);
        } catch (LogicException $exception) {
            self::assertSame('Retrofit: Could not find a proxy factory for Tebru\Retrofit\Test\Mock\Unit\RetrofitTest\ApiClient', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testCreateThrowsExceptionWithInvalidHeaderSyntax()
    {
        $retrofit = $this->retrofitBuilder->build();

        /** @var InvalidSyntaxApiClient $service */
        $service = $retrofit->create(InvalidSyntaxApiClient::class);

        try {
            $service->get();
        } catch (RuntimeException $exception) {
            self::assertSame('Retrofit: Header in an incorrect format.  Expected "Name: value"', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }
}

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Builder;

use PhpParser\Lexer;
use PhpParser\Parser;
use PhpParser\PrettyPrinter\Standard;
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
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Standard
     */
    private $printer;

    public function setUp()
    {
        parent::setUp();

        $this->parser = new Parser(new Lexer());
        $this->printer = new Standard();
    }

    private function assertResponse($response, $file)
    {
        $parsed = $this->parser->parse('<?php' . PHP_EOL . $response);
        $printed = $this->printer->prettyPrintFile($parsed) . PHP_EOL;
        $filename = sprintf('%s/resources/MethodBodyBuilder/%s.php', TEST_DIR, $file);
        $expected = file_get_contents($filename);

        try {
            $this->assertSame($expected, $printed);
        } catch (\Exception $e) {
            file_put_contents($filename, $printed);

            throw $e;
        }
    }

    public function testCanBuildGetRequest()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('GET');
        $builder->setFormUrlEncoded(true);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setQueries(['foo' => 'bar']);
        $builder->setQueryMap('$map');
        $builder->setReturnType('array');

        $response = $builder->build();

        $this->assertResponse($response, 'can_build_get_request');
    }

    public function testQueryMapNoQueries()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('GET');
        $builder->setFormUrlEncoded(true);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setQueryMap('$map');
        $builder->setReturnType('array');

        $response = $builder->build();

        $this->assertResponse($response, 'query_map_no_queries');
    }

    public function testNoQueryMap()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('GET');
        $builder->setFormUrlEncoded(true);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setQueries(['foo' => 'bar']);
        $builder->setReturnType('array');

        $response = $builder->build();

        $this->assertResponse($response, 'no_query_map');
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

    public function testJsonBodyFromArray()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setHeaders(['Content-Type' => 'application/json']);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $builder->setBodyIsObject(false);
        $builder->setBodyIsArray(true);
        $builder->setJsonEncode(true);

        $response = $builder->build();

        $this->assertResponse($response, 'json_body_from_array');
    }

    public function testJsonBodyFromObject()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setHeaders(['Content-Type' => 'application/json']);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $context = ['groups' => ['test' => 'group'], 'version' => 1, 'serializeNull' => true, 'enableMaxDepthChecks' => true, 'attributes' => ['foo' => 'bar']];
        $builder->setSerializationContext($context);
        $builder->setBodyIsObject(true);
        $builder->setJsonEncode(true);

        $response = $builder->build();

        $this->assertResponse($response, 'json_body_from_object');
    }

    public function testJsonBodyFromSerializableObject()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setHeaders(['Content-Type' => 'application/json']);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $builder->setBodyIsObject(true);
        $builder->setBodyIsJsonSerializable(true);
        $builder->setJsonEncode(true);

        $response = $builder->build();

        $this->assertResponse($response, 'json_body_from_serializable_object');
    }

    public function testJsonBodyFromParts()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setHeaders(['Content-Type' => 'application/json']);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBodyParts(['foo' => '$bar']);
        $builder->setJsonEncode(true);

        $response = $builder->build();

        $this->assertResponse($response, 'json_body_from_parts');
    }

    public function testJsonBodyFromString()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setHeaders(['Content-Type' => 'application/json']);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBody('$fooBody');
        $builder->setJsonEncode(true);

        $response = $builder->build();

        $this->assertResponse($response, 'json_body_from_string');
    }

    public function testFormBodyFromArray()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setHeaders(['Content-Type' => 'application/x-www-form-urlencoded']);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $builder->setBodyIsArray(true);
        $builder->setFormUrlEncoded(true);

        $response = $builder->build();

        $this->assertResponse($response, 'form_body_from_array');
    }

    public function testFormBodyFromObject()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setHeaders(['Content-Type' => 'application/x-www-form-urlencoded']);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $context = ['groups' => ['test' => 'group'], 'version' => 1, 'serializeNull' => true, 'enableMaxDepthChecks' => true, 'attributes' => ['foo' => 'bar']];
        $builder->setSerializationContext($context);
        $builder->setBodyIsObject(true);
        $builder->setFormUrlEncoded(true);

        $response = $builder->build();

        $this->assertResponse($response, 'form_body_from_object');
    }

    public function testFormBodyFromSerializableObject()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setHeaders(['Content-Type' => 'application/x-www-form-urlencoded']);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $builder->setBodyIsObject(true);
        $builder->setBodyIsJsonSerializable(true);
        $builder->setFormUrlEncoded(true);

        $response = $builder->build();

        $this->assertResponse($response, 'form_body_from_serializable_object');
    }

    public function testFormBodyFromParts()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setHeaders(['Content-Type' => 'application/x-www-form-urlencoded']);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBodyParts(['foo' => '$bar']);
        $builder->setFormUrlEncoded(true);

        $response = $builder->build();

        $this->assertResponse($response, 'form_body_from_parts');
    }

    public function testFormBodyFromString()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setHeaders(['Content-Type' => 'application/json']);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBody('$fooBody');
        $builder->setFormUrlEncoded(true);

        $response = $builder->build();

        $this->assertResponse($response, 'form_body_from_string');
    }

    public function testMultipartFromArray()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setHeaders(['Content-Type' => 'multipart/form-data']);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $builder->setBodyIsArray(true);
        $builder->setMultipartEncoded(true);
        $builder->setBoundaryId('1234');

        $response = $builder->build();

        $this->assertResponse($response, 'multipart_body_from_array');
    }

    public function testMultipartFromObject()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setHeaders(['Content-Type' => 'multipart/form-data']);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $context = ['groups' => ['test' => 'group'], 'version' => 1, 'serializeNull' => true, 'enableMaxDepthChecks' => true, 'attributes' => ['foo' => 'bar']];
        $builder->setSerializationContext($context);
        $builder->setBodyIsObject(true);
        $builder->setMultipartEncoded(true);
        $builder->setBoundaryId('1234');

        $response = $builder->build();

        $this->assertResponse($response, 'multipart_body_from_object');
    }

    public function testMultipartFromSerializableObject()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setHeaders(['Content-Type' => 'multipart/form-data']);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $builder->setBodyIsObject(true);
        $builder->setBodyIsJsonSerializable(true);
        $builder->setMultipartEncoded(true);
        $builder->setBoundaryId('1234');

        $response = $builder->build();

        $this->assertResponse($response, 'multipart_body_from_serializable_object');
    }

    public function testMultipartFromParts()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setHeaders(['Content-Type' => 'multipart/form-data']);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBodyParts(['foo' => '$bar']);
        $builder->setMultipartEncoded(true);
        $builder->setBoundaryId('1234');

        $response = $builder->build();

        $this->assertResponse($response, 'multipart_body_from_parts');
    }

    public function testMultipartBodyFromString()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setHeaders(['Content-Type' => 'application/json']);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBody('$fooBody');
        $builder->setMultipartEncoded(true);
        $builder->setBoundaryId('1234');

        $response = $builder->build();

        $this->assertResponse($response, 'multipart_body_from_string');
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Cannot have both @Body and @Part annotations
     */
    public function testBodyAndPartsThrowsException()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setFormUrlEncoded(true);
        $builder->setUri('/path');
        $builder->setBody('$body');
        $builder->setBodyParts(['foo' => 'bar']);
        $builder->build();
    }

    public function testBodyOptional()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setFormUrlEncoded(true);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $builder->setBodyIsObject(true);
        $builder->setBodyIsOptional(true);
        $builder->setBodyDefaultValue('null');
        $builder->setReturnType(MockUser::class);

        $response = $builder->build();

        $this->assertResponse($response, 'body_optional');
    }

    public function testResponseReturn()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('GET');
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $context = ['groups' => ['test' => 'group'], 'version' => 1, 'serializeNull' => true, 'enableMaxDepthChecks' => true, 'attributes' => ['foo' => 'bar']];
        $builder->setDeserializationContext($context);
        $builder->setReturnType('Response<Tebru\Retrofit\Test\Mock\MockUser>');

        $response = $builder->build();

        $this->assertResponse($response, 'response_return');
    }

    public function testOptionalCallback()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('GET');
        $builder->setFormUrlEncoded(true);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/get');
        $builder->setCallback('$callback');
        $builder->setCallbackOptional(true);
        $builder->setReturnType('array');

        $response = $builder->build();

        $this->assertResponse($response, 'optional_callback');
    }

    public function testRequiredCallback()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('GET');
        $builder->setFormUrlEncoded(true);
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/get');
        $builder->setCallback('$callback');
        $builder->setCallbackOptional(false);
        $builder->setReturnType('array');

        $response = $builder->build();

        $this->assertResponse($response, 'required_callback');
    }
}

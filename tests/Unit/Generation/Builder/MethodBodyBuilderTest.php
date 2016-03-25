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

    public function testSimpleBody()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $builder->setBodyIsObject(false);
        $builder->setBodyIsArray(true);
        $builder->setReturnType('raw');

        $response = $builder->build();

        $this->assertResponse($response, 'simple_body');
    }

    public function testJsonBody()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $builder->setBodyIsObject(false);
        $builder->setBodyIsArray(true);
        $builder->setJsonEncode(true);
        $builder->setReturnType('raw');

        $response = $builder->build();

        $this->assertResponse($response, 'json_body');
    }

    public function testSimpleBodyString()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $builder->setBodyIsObject(false);
        $builder->setBodyIsArray(false);
        $builder->setReturnType('Response<array>');

        $response = $builder->build();

        $this->assertResponse($response, 'simple_body_string');
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
        $builder->setBaseUrl('$this->baseUrl');
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

        $this->assertResponse($response, 'can_build_post_request');
    }

    public function testCanBuildPostRequestParts()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBodyParts(['foo' => 'bar']);
        $context['serializeNull'] = true;
        $builder->setDeserializationContext($context);
        $builder->setReturnType(MockUser::class);

        $response = $builder->build();

        $this->assertResponse($response, 'can_build_post_request_parts');
    }

    public function testCanBuildPostRequestPartsJsonEncoded()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBodyParts(['foo' => 'bar']);
        $builder->setJsonEncode(true);
        $context['serializeNull'] = true;
        $builder->setDeserializationContext($context);
        $builder->setReturnType(MockUser::class);

        $response = $builder->build();

        $this->assertResponse($response, 'can_build_post_request_parts_json_encoded');
    }

    public function testBodyObjectFormEncoded()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $builder->setBodyIsObject(true);
        $builder->setReturnType(MockUser::class);

        $response = $builder->build();

        $this->assertResponse($response, 'body_object_form_encoded');
    }

    public function testBodyOptional()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
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

    public function testBodyJsonSerializable()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('POST');
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/path');
        $builder->setBody('$body');
        $builder->setBodyIsObject(true);
        $builder->setBodyIsJsonSerializable(true);
        $builder->setReturnType(MockUser::class);

        $response = $builder->build();

        $this->assertResponse($response, 'body_json_serializable');
    }

    public function testOptionalCallback()
    {
        $builder = new MethodBodyBuilder();
        $builder->setRequestMethod('GET');
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
        $builder->setBaseUrl('$this->baseUrl');
        $builder->setUri('/get');
        $builder->setCallback('$callback');
        $builder->setCallbackOptional(false);
        $builder->setReturnType('array');

        $response = $builder->build();

        $this->assertResponse($response, 'required_callback');
    }
}

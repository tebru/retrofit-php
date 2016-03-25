<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Builder;

use Tebru;
use Tebru\Dynamo\Model\Body;

/**
 * Class MethodBodyBuilder
 *
 * @author Nate Brunette <n@tebru.net>
 */
class MethodBodyBuilder
{
    /**
     * @var Body
     */
    private $methodBody;
    
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * Request method
     *
     * @var string
     */
    private $requestMethod;

    /**
     * Request uri
     *
     * @var string
     */
    private $uri;

    /**
     * Array of query parameters
     *
     * @var array
     */
    private $queries = [];

    /**
     * Variable name of query map argument
     *
     * @var string
     */
    private $queryMap;

    /**
     * Request headers
     *
     * @var array
     */
    private $headers = [];

    /**
     * A string of php code to describe how to create the body
     *
     * @var string
     */
    private $body;

    /**
     * An array of body parts
     *
     * @var array
     */
    private $bodyParts = [];

    /**
     * True if the body is an object
     *
     * @var bool
     */
    private $bodyIsObject = false;

    /**
     * True if the body is an optional paramter
     *
     * @var bool
     */
    private $bodyIsOptional = false;

    /**
     * The default body value
     *
     * @var mixed
     */
    private $bodyDefaultValue;

    /**
     * True if the body implements \JsonSerializable
     *
     * @var boolean
     */
    private $bodyIsJsonSerializable;

    /**
     * True if the body is an array
     *
     * @var bool
     */
    private $bodyIsArray = false;

    /**
     * If we should json encode the body
     *
     * @var bool
     */
    private $jsonEncode = false;

    /**
     * Method return type
     *
     * @var string
     */
    private $returnType = 'array';

    /**
     * JMS Serializer serialization context
     *
     * @var array
     */
    private $serializationContext = [];

    /**
     * JMS Serializer deserialization attributes
     *
     * @var array
     */
    private $deserializationContext = [];

    /**
     * Request callback variable name
     *
     * @var string
     */
    private $callback;

    /**
     * Async callback is optional
     *
     * @var bool
     */
    private $callbackOptional = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->methodBody = new Body();
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param string $requestMethod
     */
    public function setRequestMethod($requestMethod)
    {
        $this->requestMethod = $requestMethod;
    }

    /**
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @param array $queries
     */
    public function setQueries(array $queries)
    {
        $this->queries = $queries;
    }

    /**
     * @param string $queryMap
     */
    public function setQueryMap($queryMap)
    {
        $this->queryMap = $queryMap;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @param array $bodyParts
     */
    public function setBodyParts(array $bodyParts)
    {
        $this->bodyParts = $bodyParts;
    }

    /**
     * @param boolean $bodyIsObject
     */
    public function setBodyIsObject($bodyIsObject)
    {
        $this->bodyIsObject = $bodyIsObject;
    }

    /**
     * @param boolean $bodyIsOptional
     */
    public function setBodyIsOptional($bodyIsOptional)
    {
        $this->bodyIsOptional = $bodyIsOptional;
    }

    /**
     * @param mixed $bodyDefaultValue
     */
    public function setBodyDefaultValue($bodyDefaultValue)
    {
        $this->bodyDefaultValue = $bodyDefaultValue;
    }

    /**
     * @param boolean $bodyIsJsonSerializable
     */
    public function setBodyIsJsonSerializable($bodyIsJsonSerializable)
    {
        $this->bodyIsJsonSerializable = $bodyIsJsonSerializable;
    }

    /**
     * @param boolean $bodyIsArray
     */
    public function setBodyIsArray($bodyIsArray)
    {
        $this->bodyIsArray = $bodyIsArray;
    }

    /**
     * @param boolean $jsonEncode
     */
    public function setJsonEncode($jsonEncode)
    {
        $this->jsonEncode = $jsonEncode;
    }

    /**
     * @param string $returnType
     */
    public function setReturnType($returnType)
    {
        $this->returnType = $returnType;
    }

    /**
     * @param array $serializationContext
     */
    public function setSerializationContext(array $serializationContext)
    {
        $this->serializationContext = $serializationContext;
    }

    /**
     * @param array $deserializationContext
     */
    public function setDeserializationContext(array $deserializationContext)
    {
        $this->deserializationContext = $deserializationContext;
    }

    /**
     * @param string $callback
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param boolean $callbackOptional
     */
    public function setCallbackOptional($callbackOptional)
    {
        $this->callbackOptional = $callbackOptional;
    }

    /**
     * Build the method body
     *
     * @return string
     */
    public function build()
    {
        $this->createRequestUrl();
        $this->createHeaders();
        $this->createBody();
        $this->createResponse();
        $this->createReturns();

        return (string)$this->methodBody;
    }

    /**
     * Build the request url
     */
    private function createRequestUrl()
    {
        Tebru\assertNotNull($this->uri, 'Request annotation not found (e.g. @GET, @POST)');

        $baseUrl = (null !== $this->baseUrl) ? $this->baseUrl : '$this->baseUrl';

        // request request params using http_build_query if we have a query map
        if (null !== $this->queryMap) {
            // if we have regular queries, add them to the query builder
            if (!empty($this->queries)) {
                $queryArray = $this->arrayToString($this->queries);
                $this->methodBody->add('$queryString = http_build_query(%s + %s);', $queryArray, $this->queryMap);
            } else {
                $this->methodBody->add('$queryString = http_build_query(%s);', $this->queryMap);
            }

            $this->methodBody->add('$requestUrl = %s . "%s?" . $queryString;', $baseUrl, $this->uri);

            // if we have queries, add them to the request url
        } elseif (!empty($this->queries)) {
            $queryArray = $this->arrayToString($this->queries);
            $this->methodBody->add('$queryString = http_build_query(%s);', $queryArray);
            $this->methodBody->add('$requestUrl = %s . "%s" . "?" . $queryString;', $baseUrl, $this->uri);
        } else {
            $this->methodBody->add('$requestUrl = %s . "%s";', $baseUrl, $this->uri);
        }
    }

    /**
     * Build the headers
     */
    private function createHeaders()
    {
        if (empty($this->headers)) {
            $this->methodBody->add('$headers = [];');

            return;
        }

        $this->methodBody->add('$headers = %s;', $this->arrayToString($this->headers));
    }

    /**
     * Build the request body
     */
    private function createBody()
    {
        if (null === $this->body && empty($this->bodyParts)) {
            $this->methodBody->add('$body = null;');

            return;
        }

        Tebru\assertThat(null === $this->body || empty($this->bodyParts), 'Cannot have both @Body and @Part annotations');

        if ($this->bodyIsObject) {
            if ($this->bodyIsOptional) {
                $this->methodBody->add('if (null !== %s) {', $this->body);
            }

            if ($this->bodyIsJsonSerializable) {
                $this->methodBody->add('$body = json_encode(%s);', $this->body);
            } else {
                if (!empty($this->serializationContext)) {
                    $this->methodBody->add('$context = \JMS\Serializer\SerializationContext::create();');
                    $this->createContext($this->serializationContext);
                    $this->methodBody->add('$body = $this->serializer->serialize(%s, "json", $context);', $this->body);
                } else {
                    $this->methodBody->add('$body = $this->serializer->serialize(%s, "json");', $this->body);
                }
            }


            if (false === $this->jsonEncode) {
                $this->methodBody->add('$body = json_decode($body, true);');
                $this->methodBody->add('$body = \Tebru\Retrofit\Generation\Manipulator\BodyManipulator::boolToString($body);');
                $this->methodBody->add('$body = http_build_query($body);');
            }

            if ($this->bodyIsOptional) {
                $this->methodBody->add('} else { $body = %s; }', $this->bodyDefaultValue);
            }
        } elseif ($this->bodyIsArray) {
            (true === $this->jsonEncode)
                ? $this->methodBody->add('$body = json_encode(%s);', $this->body)
                : $this->methodBody->add('$body = http_build_query(%s);', $this->body);
        } elseif (null !== $this->body) {
            $this->methodBody->add('$body = %s;', $this->body);
        } else {
            (true === $this->jsonEncode)
                ? $this->methodBody->add('$body = json_encode(%s);', $this->arrayToString($this->bodyParts))
                : $this->methodBody->add('$body = http_build_query(%s);', $this->arrayToString($this->bodyParts));
        }
    }

    /**
     * Build the response
     */
    private function createResponse()
    {
        $this->methodBody->add('$request = new \GuzzleHttp\Psr7\Request("%s", $requestUrl, $headers, $body);', strtoupper($this->requestMethod));
        $this->methodBody->add('$this->logger->debug("Created Request", ["request" => ["method" => $request->getMethod(), "uri" => rawurldecode((string)$request->getUri()), "headers" => $request->getHeaders(), "body" => (string)$request->getBody()]]);');
        $this->methodBody->add('$beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);');
        $this->methodBody->add('$this->eventDispatcher->dispatch("retrofit.beforeSend", $beforeSendEvent);');
        $this->methodBody->add('$request = $beforeSendEvent->getRequest();');
        $this->methodBody->add('try {');

        if ($this->callback !== null && $this->callbackOptional) {
            $this->methodBody->add('if (%s !== null) {', $this->callback);
            $this->methodBody->add('$response = $this->client->sendAsync($request, %s);', $this->callback);
            $this->methodBody->add('} else {');
            $this->methodBody->add('$response = $this->client->send($request);');
            $this->methodBody->add('}');
        } elseif ($this->callback !== null && !$this->callbackOptional) {
            $this->methodBody->add('$response = $this->client->sendAsync($request, %s);', $this->callback);
        } else {
            $this->methodBody->add('$response = $this->client->send($request);');
        }

        $this->methodBody->add('} catch (\Exception $exception) {');
        $this->methodBody->add('$this->logger->error("Caught Exception", ["exception" => $exception]);');
        $this->methodBody->add('$apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);');
        $this->methodBody->add('$this->eventDispatcher->dispatch("retrofit.apiException", $apiExceptionEvent);');
        $this->methodBody->add('$exception = $apiExceptionEvent->getException();');
        $this->methodBody->add('throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);');
        $this->methodBody->add('}');
        $this->methodBody->add('$this->logger->debug("API Response", ["response" => $response]);');
        $this->methodBody->add('$afterSendEvent = new \Tebru\Retrofit\Event\AfterSendEvent($request, $response);');
        $this->methodBody->add('$this->eventDispatcher->dispatch("retrofit.afterSend", $afterSendEvent);');
        $this->methodBody->add('$response = $afterSendEvent->getResponse();');
    }

    /**
     * Build the return
     */
    private function createReturns()
    {
        if ($this->callback !== null && $this->callbackOptional) {
            $this->methodBody->add('if (%s !== null) {', $this->callback);
            $this->methodBody->add('$returnEvent = new \Tebru\Retrofit\Event\ReturnEvent(null);');
            $this->methodBody->add('$this->eventDispatcher->dispatch("retrofit.return", $returnEvent);');
            $this->methodBody->add('return $returnEvent->getReturn();');
            $this->methodBody->add('}');
        } elseif ($this->callback !== null && !$this->callbackOptional) {
            $this->methodBody->add('$returnEvent = new \Tebru\Retrofit\Event\ReturnEvent(null);');
            $this->methodBody->add('$this->eventDispatcher->dispatch("retrofit.return", $returnEvent);');
            $this->methodBody->add('return $returnEvent->getReturn();');

            return;
        }

        $matches = [];
        $returnType = $this->returnType;
        $responseReturn = false;
        preg_match('/^Response<(.+)>$/', $returnType, $matches);

        if (isset($matches[1])) {
            $returnType = $matches[1];
            $responseReturn = true;
        }

        $this->methodBody->add(
            '$retrofitResponse = new \Tebru\Retrofit\Http\Response($response, "%s", $this->serializer, %s);',
            $returnType,
            $this->arrayToString($this->deserializationContext)
        );

        if ($responseReturn) {
            $this->methodBody->add('$return = $retrofitResponse;');
        } else {
            $this->methodBody->add('$return = $retrofitResponse->body();');
        }

        $this->methodBody->add('$returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);');
        $this->methodBody->add('$this->eventDispatcher->dispatch("retrofit.return", $returnEvent);');
        $this->methodBody->add('return $returnEvent->getReturn();');
    }

    /**
     * Build the serialization context
     *
     * @param $context
     */
    private function createContext(&$context)
    {
        if (!empty($context['groups'])) {
            $this->methodBody->add('$context->setGroups(%s);', $this->arrayToString($context['groups']));
        }

        if (!empty($context['version'])) {
            $this->methodBody->add('$context->setVersion(%d);', (int)$context['version']);
        }

        if (!empty($context['serializeNull'])) {
            $this->methodBody->add('$context->setSerializeNull(%d);', (bool)$context['serializeNull']);
        }

        if (!empty($context['enableMaxDepthChecks'])) {
            $this->methodBody->add('$context->enableMaxDepthChecks();');
        }

        if (!empty($context['attributes'])) {
            foreach ($context['attributes'] as $key => $value) {
                $this->methodBody->add('$context->setAttribute("%s", "%s");', $key, $value);
            }
        }
    }

    /**
     * Create a string representation of an array
     *
     * @param array $array
     * @return string
     */
    private function arrayToString(array $array)
    {
        $string = var_export($array, true);
        $string = preg_replace('/\'\$(.+)\'/', '$' . '\\1', $string);

        return $string;
    }
}

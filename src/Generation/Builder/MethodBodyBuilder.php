<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Builder;

use Tebru;

/**
 * Class MethodBodyBuilder
 *
 * @author Nate Brunette <n@tebru.net>
 */
class MethodBodyBuilder
{
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
        $body = [];
        $body = $this->createRequestUrl($body);
        $body = $this->createHeaders($body);
        $body = $this->createBody($body);
        $body = $this->createResponse($body);
        $body = $this->createReturns($body);

        return implode($body);
    }

    /**
     * Build the request url
     *
     * @param array $body
     * @return array
     */
    private function createRequestUrl(array $body)
    {
        Tebru\assertNotNull($this->uri, 'Request annotation not found (e.g. @GET, @POST)');

        $baseUrl = (null !== $this->baseUrl) ? $this->baseUrl : '$this->baseUrl';

        // request request params using http_build_query if we have a query map
        if (null !== $this->queryMap) {
            // if we have regular queries, add them to the query builder
            if (!empty($this->queries)) {
                $queryArray = $this->arrayToString($this->queries);
                $body[] = sprintf('$queryString = urldecode(http_build_query(%s + %s));', $queryArray, $this->queryMap);
            } else {
                $body[] = sprintf('$queryString = urldecode(http_build_query(%s));', $this->queryMap);
            }

            $body[] = sprintf('$requestUrl = %s . "%s?" . $queryString;', $baseUrl, $this->uri);

            // if we have queries, add them to the request url
        } elseif (!empty($this->queries)) {
            $queryArray = $this->arrayToString($this->queries);
            $body[] = sprintf('$queryString = urldecode(http_build_query(%s));', $queryArray);
            $body[] = sprintf('$requestUrl = %s . "%s" . "?" . $queryString;', $baseUrl, $this->uri);
        } else {
            $body[] = sprintf('$requestUrl = %s . "%s";', $baseUrl, $this->uri);
        }

        return $body;
    }

    /**
     * Build the headers
     *
     * @param array $body
     * @return array
     */
    private function createHeaders(array $body)
    {
        if (empty($this->headers)) {
            $body[] = '$headers = [];';

            return $body;
        }

        $body[] = sprintf('$headers = %s;', $this->arrayToString($this->headers));

        return $body;
    }

    /**
     * Build the request body
     *
     * @param array $body
     * @return array
     */
    private function createBody(array $body)
    {
        if (null === $this->body && empty($this->bodyParts)) {
            $body[] = '$body = null;';

            return $body;
        }

        Tebru\assertThat(null === $this->body || empty($this->bodyParts), 'Cannot have both @Body and @Part annotations');

        if ($this->bodyIsObject) {
            if ($this->bodyIsOptional) {
                $body[] = sprintf('if (null !== %s) {', $this->body);
            }

            if ($this->bodyIsJsonSerializable) {
                $body[] = sprintf('$body = json_encode(%s);', $this->body);
            } else {
                if (!empty($this->serializationContext)) {
                    $body[] = sprintf('$context = \JMS\Serializer\SerializationContext::create();');
                    $body = $this->createContext($body, $this->serializationContext);
                    $body[] = sprintf('$body = $this->serializer->serialize(%s, "json", $context);', $this->body);
                } else {
                    $body[] = sprintf('$body = $this->serializer->serialize(%s, "json");', $this->body);
                }
            }


            if (false === $this->jsonEncode) {
                $body[] = sprintf('$body = json_decode($body, true);');
                $body[] = sprintf('$body = \Tebru\Retrofit\Generation\Manipulator\BodyManipulator::boolToString($body);');
                $body[] = sprintf('$body = http_build_query($body);');
            }

            if ($this->bodyIsOptional) {
                $body[] = sprintf('} else { $body = %s; }', $this->bodyDefaultValue);
            }
        } elseif ($this->bodyIsArray) {
            $body[] = (true === $this->jsonEncode)
                ? sprintf('$body = json_encode(%s);', $this->body)
                : sprintf('$body = http_build_query(%s);', $this->body);
        } elseif (null !== $this->body) {
            $body[] = sprintf('$body = %s;', $this->body);
        } else {
            $body[] = (true === $this->jsonEncode)
                ? sprintf('$body = json_encode(%s);', $this->arrayToString($this->bodyParts))
                : sprintf('$body = http_build_query(%s);', $this->arrayToString($this->bodyParts));
        }

        return $body;
    }

    /**
     * Build the response
     *
     * @param array $body
     * @return array
     */
    private function createResponse(array $body)
    {
        $body[] = sprintf('$request = new \GuzzleHttp\Psr7\Request("%s", $requestUrl, $headers, $body);', strtoupper($this->requestMethod));
        $body[] = sprintf('$this->logger->debug("Created Request", ["request" => ["method" => $request->getMethod(), "uri" => (string)$request->getUri(), "headers" => $request->getHeaders(), "body" => (string)$request->getBody()]]);');
        $body[] = sprintf('$this->logger->info("Dispatching BeforeSendEvent");');
        $body[] = sprintf('$this->eventDispatcher->dispatch("retrofit.beforeSend", new \Tebru\Retrofit\Event\BeforeSendEvent($request));');
        $body[] = sprintf('try {');

        if ($this->callback !== null && $this->callbackOptional) {
            $body[] = sprintf('if (%s !== null) {', $this->callback);
            $body[] = sprintf('$this->logger->info("Sending Asynchronous Request");');
            $body[] = sprintf('$response = $this->client->sendAsync($request, %s);', $this->callback);
            $body[] = sprintf('} else {');
            $body[] = sprintf('$this->logger->info("Sending Synchronous Request");');
            $body[] = sprintf('$response = $this->client->send($request->getMethod(), (string)$request->getUri(), $request->getHeaders(), (string)$request->getBody());');
            $body[] = sprintf('}');
        } elseif ($this->callback !== null && !$this->callbackOptional) {
            $body[] = sprintf('$this->logger->info("Sending Asynchronous Request");');
            $body[] = sprintf('$response = $this->client->sendAsync($request, %s);', $this->callback);
        } else {
            $body[] = sprintf('$this->logger->info("Sending Synchronous Request");');
            $body[] = sprintf('$response = $this->client->send($request->getMethod(), (string)$request->getUri(), $request->getHeaders(), (string)$request->getBody());');
        }

        $body[] = sprintf('} catch (\Exception $exception) {');
        $body[] = sprintf('$this->logger->error("Caught Exception", ["exception" => $exception]);');
        $body[] = sprintf('$this->logger->info("Dispatching ApiExceptionEvent");');
        $body[] = sprintf('$this->eventDispatcher->dispatch("retrofit.apiException", new \Tebru\Retrofit\Event\ApiExceptionEvent($exception));');
        $body[] = sprintf('throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);');
        $body[] = sprintf('}');
        $body[] = sprintf('$this->logger->debug("API Response", ["response" => $response]);');
        $body[] = sprintf('$this->logger->info("Dispatching AfterSendEvent");');
        $body[] = sprintf('$this->eventDispatcher->dispatch("retrofit.afterSend", new \Tebru\Retrofit\Event\AfterSendEvent($response));');

        return $body;
    }

    /**
     * Build the return
     *
     * @param array $body
     * @return array
     */
    private function createReturns(array $body)
    {
        if ($this->callback !== null && $this->callbackOptional) {
            $body[] = sprintf('if (%s !== null) {', $this->callback);
            $body[] = sprintf('$this->logger->info("Dispatching ReturnEvent");');
            $body[] = sprintf('$returnEvent = new \Tebru\Retrofit\Event\ReturnEvent(null);');
            $body[] = sprintf('$this->eventDispatcher->dispatch("retrofit.return", $returnEvent);');
            $body[] = sprintf('return $returnEvent->getReturn();');
            $body[] = sprintf('}');
        } elseif ($this->callback !== null && !$this->callbackOptional) {
            $body[] = sprintf('$this->logger->info("Dispatching ReturnEvent");');
            $body[] = sprintf('$returnEvent = new \Tebru\Retrofit\Event\ReturnEvent(null);');
            $body[] = sprintf('$this->eventDispatcher->dispatch("retrofit.return", $returnEvent);');
            $body[] = sprintf('return $returnEvent->getReturn();');

            return $body;
        }

        switch ($this->returnType) {
            case 'raw':
                $body[] = sprintf('$return = (string)$response->getBody();');
                break;
            case 'array':
                $body[] = sprintf('$return = json_decode((string)$response->getBody(), true);');
                break;
            default:
                if (!empty($this->deserializationContext)) {
                    $body[] = sprintf('$context = \JMS\Serializer\DeserializationContext::create();');
                    $body = $this->createContext($body, $this->deserializationContext);
                    $body = $this->createDepthContext($body);
                    $body[] = sprintf('$return = $this->serializer->deserialize((string)$response->getBody(), "%s", "json", $context);', $this->returnType);
                } else {
                    $body[] = sprintf('$return = $this->serializer->deserialize((string)$response->getBody(), "%s", "json");', $this->returnType);
                }
        }

        $body[] = sprintf('$this->logger->info("Dispatching ReturnEvent");');
        $body[] = sprintf('$returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);');
        $body[] = sprintf('$this->eventDispatcher->dispatch("retrofit.return", $returnEvent);');
        $body[] = sprintf('return $returnEvent->getReturn();');

        return $body;
    }

    /**
     * Build the serialization context
     *
     * @param array $body
     * @param $context
     * @return array
     */
    private function createContext(array $body, &$context)
    {
        if (!empty($context['groups'])) {
            $body[] = sprintf('$context->setGroups(%s);', $this->arrayToString($context['groups']));
        }

        if (!empty($context['version'])) {
            $body[] = sprintf('$context->setVersion(%d);', (int)$context['version']);
        }

        if (!empty($context['serializeNull'])) {
            $body[] = sprintf('$context->setSerializeNull(%d);', (int)$context['serializeNull']);
        }

        if (!empty($context['enableMaxDepthChecks'])) {
            $body[] = sprintf('$context->enableMaxDepthChecks();');
        }

        if (!empty($context['attributes'])) {
            foreach ($context['attributes'] as $key => $value) {
                $body[] = sprintf('$context->setAttribute("%s", "%s");', $key, $value);
            }
        }

        return $body;
    }

    /**
     * Build the serializer depth context
     *
     * @param array $body
     * @return array
     */
    private function createDepthContext(array $body)
    {
        if (empty($this->deserializationContext['depth'])) {
            return $body;
        }

        $contextDepth = (int)$this->deserializationContext['depth'];
        $body[] = sprintf('while ($context->getDepth() > %d) { $context->decreaseDepth(); }', $contextDepth);
        $body[] = sprintf('while ($context->getDepth() < %d) { $context->increaseDepth(); }', $contextDepth);

        return $body;
    }

    /**
     * Create a string representation of an array
     *
     * @param array $array
     * @return string
     */
    private function arrayToString(array $array)
    {
        $parts = [];
        foreach ($array as $key => $value) {
            $parts[] = (false !== strpos($value, '$')) ? sprintf('"%s" => %s', $key, $value) : sprintf('"%s" => "%s"', $key, $value);
        }

        $string = sprintf('[%s]', implode(', ', $parts));

        return $string;
    }
}

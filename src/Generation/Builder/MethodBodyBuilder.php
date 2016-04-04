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
     * If the body should be sent form urlencoded
     *
     * @var bool
     */
    private $formUrlEncoded = false;

    /**
     * If the body should be sent as multipart
     *
     * @var bool
     */
    private $multipartEncoded = false;

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
     * Boundary Id for multipart requests
     *
     * @var string
     */
    private $boundaryId;

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
     * @param boolean $formUrlEncoded
     */
    public function setFormUrlEncoded($formUrlEncoded)
    {
        $this->formUrlEncoded = $formUrlEncoded;
    }

    /**
     * @param boolean $multipartEncoded
     */
    public function setMultipartEncoded($multipartEncoded)
    {
        $this->multipartEncoded = $multipartEncoded;
    }

    /**
     * @param string $boundaryId
     */
    public function setBoundaryId($boundaryId)
    {
        $this->boundaryId = $boundaryId;
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
        Tebru\assertThat(null === $this->body || empty($this->bodyParts), 'Cannot have both @Body and @Part annotations');

        $this->createRequestUrl();
        $this->createHeaders();
        $this->createBody();
        $this->createResponse();
        $this->createReturns();

        return (string) $this->methodBody;
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
                $this->methodBody->add('$queryArray = \Tebru\Retrofit\Generation\Manipulator\QueryManipulator::boolToString(%s + %s);', $queryArray, $this->queryMap);
                $this->methodBody->add('$queryString = http_build_query($queryArray);');
            } else {
                $this->methodBody->add('$queryArray = \Tebru\Retrofit\Generation\Manipulator\QueryManipulator::boolToString(%s);', $this->queryMap);
                $this->methodBody->add('$queryString = http_build_query($queryArray);');
            }

            $this->methodBody->add('$requestUrl = %s . "%s?" . $queryString;', $baseUrl, $this->uri);

            // if we have queries, add them to the request url
        } elseif (!empty($this->queries)) {
            $queryArray = $this->arrayToString($this->queries);
            $this->methodBody->add('$queryArray = \Tebru\Retrofit\Generation\Manipulator\QueryManipulator::boolToString(%s);', $queryArray);
            $this->methodBody->add('$queryString = http_build_query($queryArray);');
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
        // body should be null if there isn't a body or parts
        if (null === $this->body && empty($this->bodyParts)) {
            $this->methodBody->add('$body = null;');

            return;
        }

        // if body is optional and 'empty' it should be null
        if ($this->bodyIsOptional) {
            $this->methodBody->add('if (empty(%s)) { $body = null; } else {', $this->body);
        }

        $this->doCreateBody();

        if ($this->bodyIsOptional) {
            $this->methodBody->add('}');
        }
    }

    /**
     * Logic to actually create body
     */
    private function doCreateBody()
    {
        // check if body is a string, set variable if it doesn't already equal '$body'
        if (!$this->bodyIsArray && !$this->bodyIsObject && empty($this->bodyParts)) {
            if ('$body' !== $this->body) {
                $this->methodBody->add('$body = %s;', $this->body);
            }

            return;
        }

        // if we're json encoding, we don't need the body as an array first
        if ($this->jsonEncode) {
            $this->createBodyJson();

            return;
        }

        // otherwise, we do need to convert the body to an array
        $this->createBodyArray();

        if ($this->formUrlEncoded) {
            $this->methodBody->add('$bodyArray = \Tebru\Retrofit\Generation\Manipulator\QueryManipulator::boolToString($bodyArray);');
            $this->methodBody->add('$body = http_build_query($bodyArray);');

            return;
        }

        // body is multipart
        if ($this->multipartEncoded) {
            $this->methodBody->add('$bodyParts = [];');
            $this->methodBody->add('foreach ($bodyArray as $key => $value) {');
            $this->methodBody->add('$file = null;');
            $this->methodBody->add('if (is_resource($value)) { $file = $value; }');
            $this->methodBody->add('if (is_string($value)) { $file = fopen($value, "r"); }');
            $this->methodBody->add('if (!is_resource($file)) { throw new \LogicException("Expected resource or file path"); }');
            $this->methodBody->add('$bodyParts[] = ["name" => $key, "contents" => $file];');
            $this->methodBody->add('}');

            $this->methodBody->add('$body = new \GuzzleHttp\Psr7\MultipartStream($bodyParts, "%s");', $this->boundaryId);
        }
    }

    /**
     * Create json body
     */
    private function createBodyJson()
    {
        // json encode arrays
        if ($this->bodyIsArray) {
            $this->methodBody->add('$body = json_encode(%s);', $this->body);

            return;
        }

        // if parts exist, json encode the parts
        if (!empty($this->bodyParts)) {
            $this->methodBody->add('$body = json_encode(%s);', $this->arrayToString($this->bodyParts));

            return;
        }

        // if it's an object, serialize it unless it implements \JsonSerializable
        if ($this->bodyIsObject) {
            if ($this->bodyIsJsonSerializable) {
                $this->methodBody->add('$body = json_encode(%s);', $this->body);

                return;
            }

            $this->serializeObject('$bodySerializationContext', '$body', $this->body);
        }
    }

    /**
     * Normalize body as array
     */
    private function createBodyArray()
    {
        // if it's already an array, set to variable
        if ($this->bodyIsArray) {
            $this->methodBody->add('$bodyArray = %s;', $this->body);

            return;
        }

        // if parts exist, set to variable
        if (!empty($this->bodyParts)) {
            $this->methodBody->add('$bodyArray = %s;', $this->arrayToString($this->bodyParts));

            return;
        }

        // if it implements \JsonSerializable, call jsonSerialize() to get array
        if ($this->bodyIsJsonSerializable) {
            $this->methodBody->add('$bodyArray = %s->jsonSerialize();', $this->body);

            return;
        }

        // otherwise, serialize and json_decode()
        $this->serializeObject('$bodySerializationContext', '$serializedBody', $this->body);
        $this->methodBody->add('$bodyArray = json_decode($serializedBody, true);');
    }

    /**
     * Helper method to serialize an object
     *
     * @param string $contextVar
     * @param string $bodyVar
     * @param string $object
     */
    private function serializeObject($contextVar, $bodyVar, $object)
    {
        $this->methodBody->add('%s = \JMS\Serializer\SerializationContext::create();', $contextVar);
        if (!empty($this->serializationContext)) {
            $this->createContext($contextVar, $this->serializationContext);
        }

        $this->methodBody->add('%s = $this->serializer->serialize(%s, "json", %s);', $bodyVar, $object, $contextVar);
    }

    /**
     * Build the response
     */
    private function createResponse()
    {
        $this->methodBody->add('$request = new \GuzzleHttp\Psr7\Request("%s", $requestUrl, $headers, $body);', strtoupper($this->requestMethod));
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
        $this->methodBody->add('$apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);');
        $this->methodBody->add('$this->eventDispatcher->dispatch("retrofit.apiException", $apiExceptionEvent);');
        $this->methodBody->add('$exception = $apiExceptionEvent->getException();');
        $this->methodBody->add('throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);');
        $this->methodBody->add('}');
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
     * @param $contextVar
     * @param $context
     */
    private function createContext($contextVar, &$context)
    {
        if (!empty($context['groups'])) {
            $this->methodBody->add('%s->setGroups(%s);', $contextVar, $this->arrayToString($context['groups']));
        }

        if (!empty($context['version'])) {
            $this->methodBody->add('%s->setVersion(%d);', $contextVar, (int) $context['version']);
        }

        if (!empty($context['serializeNull'])) {
            $this->methodBody->add('%s->setSerializeNull(%d);', $contextVar, (bool) $context['serializeNull']);
        }

        if (!empty($context['enableMaxDepthChecks'])) {
            $this->methodBody->add('%s->enableMaxDepthChecks();', $contextVar);
        }

        if (!empty($context['attributes'])) {
            foreach ($context['attributes'] as $key => $value) {
                $this->methodBody->add('%s->setAttribute("%s", "%s");', $contextVar, $key, $value);
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

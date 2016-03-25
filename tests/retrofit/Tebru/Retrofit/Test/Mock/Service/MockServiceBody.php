<?php

namespace Tebru\Retrofit\Generated\Tebru\Retrofit\Test\Mock\Service;

class MockServiceBody implements \Tebru\Retrofit\Test\Mock\Service\MockServiceBody
{
    private $baseUrl = null;
    private $client = null;
    private $serializer = null;
    private $eventDispatcher = null;
    public function __construct($baseUrl, \Tebru\Retrofit\Adapter\HttpClientAdapter $client, \JMS\Serializer\SerializerInterface $serializer, \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher)
    {
        $this->baseUrl = $baseUrl;
        $this->client = $client;
        $this->serializer = $serializer;
        $this->eventDispatcher = $eventDispatcher;
    }
    public function simpleBody(array $myBody)
    {
        $requestUrl = $this->baseUrl . '/post';
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $body = http_build_query($myBody);
        $request = new \GuzzleHttp\Psr7\Request('POST', $requestUrl, $headers, $body);
        $beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);
        $this->eventDispatcher->dispatch('retrofit.beforeSend', $beforeSendEvent);
        $request = $beforeSendEvent->getRequest();
        try {
            $response = $this->client->send($request);
        } catch (\Exception $exception) {
            $apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);
            $this->eventDispatcher->dispatch('retrofit.apiException', $apiExceptionEvent);
            $exception = $apiExceptionEvent->getException();
            throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);
        }
        $afterSendEvent = new \Tebru\Retrofit\Event\AfterSendEvent($request, $response);
        $this->eventDispatcher->dispatch('retrofit.afterSend', $afterSendEvent);
        $response = $afterSendEvent->getResponse();
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array());
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function bodyChangeName(array $foo)
    {
        $requestUrl = $this->baseUrl . '/post';
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $body = http_build_query($foo);
        $request = new \GuzzleHttp\Psr7\Request('POST', $requestUrl, $headers, $body);
        $beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);
        $this->eventDispatcher->dispatch('retrofit.beforeSend', $beforeSendEvent);
        $request = $beforeSendEvent->getRequest();
        try {
            $response = $this->client->send($request);
        } catch (\Exception $exception) {
            $apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);
            $this->eventDispatcher->dispatch('retrofit.apiException', $apiExceptionEvent);
            $exception = $apiExceptionEvent->getException();
            throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);
        }
        $afterSendEvent = new \Tebru\Retrofit\Event\AfterSendEvent($request, $response);
        $this->eventDispatcher->dispatch('retrofit.afterSend', $afterSendEvent);
        $response = $afterSendEvent->getResponse();
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array());
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function objectBody(\Tebru\Retrofit\Test\Mock\MockUser $user)
    {
        $requestUrl = $this->baseUrl . '/post';
        $headers = array('Content-Type' => 'application/json');
        $body = $this->serializer->serialize($user, 'json');
        $request = new \GuzzleHttp\Psr7\Request('POST', $requestUrl, $headers, $body);
        $beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);
        $this->eventDispatcher->dispatch('retrofit.beforeSend', $beforeSendEvent);
        $request = $beforeSendEvent->getRequest();
        try {
            $response = $this->client->send($request);
        } catch (\Exception $exception) {
            $apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);
            $this->eventDispatcher->dispatch('retrofit.apiException', $apiExceptionEvent);
            $exception = $apiExceptionEvent->getException();
            throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);
        }
        $afterSendEvent = new \Tebru\Retrofit\Event\AfterSendEvent($request, $response);
        $this->eventDispatcher->dispatch('retrofit.afterSend', $afterSendEvent);
        $response = $afterSendEvent->getResponse();
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array());
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function objectBodyChangeName(\Tebru\Retrofit\Test\Mock\MockUser $foo)
    {
        $requestUrl = $this->baseUrl . '/post';
        $headers = array('Content-Type' => 'application/json');
        $body = $this->serializer->serialize($foo, 'json');
        $request = new \GuzzleHttp\Psr7\Request('POST', $requestUrl, $headers, $body);
        $beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);
        $this->eventDispatcher->dispatch('retrofit.beforeSend', $beforeSendEvent);
        $request = $beforeSendEvent->getRequest();
        try {
            $response = $this->client->send($request);
        } catch (\Exception $exception) {
            $apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);
            $this->eventDispatcher->dispatch('retrofit.apiException', $apiExceptionEvent);
            $exception = $apiExceptionEvent->getException();
            throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);
        }
        $afterSendEvent = new \Tebru\Retrofit\Event\AfterSendEvent($request, $response);
        $this->eventDispatcher->dispatch('retrofit.afterSend', $afterSendEvent);
        $response = $afterSendEvent->getResponse();
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array());
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function objectBodyAsFromEncoded(\Tebru\Retrofit\Test\Mock\MockUser $user)
    {
        $requestUrl = $this->baseUrl . '/post';
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $body = $this->serializer->serialize($user, 'json');
        $body = json_decode($body, true);
        $body = \Tebru\Retrofit\Generation\Manipulator\BodyManipulator::boolToString($body);
        $body = http_build_query($body);
        $request = new \GuzzleHttp\Psr7\Request('POST', $requestUrl, $headers, $body);
        $beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);
        $this->eventDispatcher->dispatch('retrofit.beforeSend', $beforeSendEvent);
        $request = $beforeSendEvent->getRequest();
        try {
            $response = $this->client->send($request);
        } catch (\Exception $exception) {
            $apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);
            $this->eventDispatcher->dispatch('retrofit.apiException', $apiExceptionEvent);
            $exception = $apiExceptionEvent->getException();
            throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);
        }
        $afterSendEvent = new \Tebru\Retrofit\Event\AfterSendEvent($request, $response);
        $this->eventDispatcher->dispatch('retrofit.afterSend', $afterSendEvent);
        $response = $afterSendEvent->getResponse();
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array());
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function objectBodyOptional(\Tebru\Retrofit\Test\Mock\MockUser $user = null)
    {
        $requestUrl = $this->baseUrl . '/post';
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        if (null !== $user) {
            $body = $this->serializer->serialize($user, 'json');
            $body = json_decode($body, true);
            $body = \Tebru\Retrofit\Generation\Manipulator\BodyManipulator::boolToString($body);
            $body = http_build_query($body);
        } else {
            $body = null;
        }
        $request = new \GuzzleHttp\Psr7\Request('POST', $requestUrl, $headers, $body);
        $beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);
        $this->eventDispatcher->dispatch('retrofit.beforeSend', $beforeSendEvent);
        $request = $beforeSendEvent->getRequest();
        try {
            $response = $this->client->send($request);
        } catch (\Exception $exception) {
            $apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);
            $this->eventDispatcher->dispatch('retrofit.apiException', $apiExceptionEvent);
            $exception = $apiExceptionEvent->getException();
            throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);
        }
        $afterSendEvent = new \Tebru\Retrofit\Event\AfterSendEvent($request, $response);
        $this->eventDispatcher->dispatch('retrofit.afterSend', $afterSendEvent);
        $response = $afterSendEvent->getResponse();
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array());
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function objectBodyJsonSerializable(\Tebru\Retrofit\Test\Mock\MockUser $user)
    {
        $requestUrl = $this->baseUrl . '/post';
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $body = json_encode($user);
        $body = json_decode($body, true);
        $body = \Tebru\Retrofit\Generation\Manipulator\BodyManipulator::boolToString($body);
        $body = http_build_query($body);
        $request = new \GuzzleHttp\Psr7\Request('POST', $requestUrl, $headers, $body);
        $beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);
        $this->eventDispatcher->dispatch('retrofit.beforeSend', $beforeSendEvent);
        $request = $beforeSendEvent->getRequest();
        try {
            $response = $this->client->send($request);
        } catch (\Exception $exception) {
            $apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);
            $this->eventDispatcher->dispatch('retrofit.apiException', $apiExceptionEvent);
            $exception = $apiExceptionEvent->getException();
            throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);
        }
        $afterSendEvent = new \Tebru\Retrofit\Event\AfterSendEvent($request, $response);
        $this->eventDispatcher->dispatch('retrofit.afterSend', $afterSendEvent);
        $response = $afterSendEvent->getResponse();
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array());
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function parts($foo, $bar)
    {
        $requestUrl = $this->baseUrl . '/post';
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $body = http_build_query(array('foo' => $foo, 'bar' => $bar));
        $request = new \GuzzleHttp\Psr7\Request('POST', $requestUrl, $headers, $body);
        $beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);
        $this->eventDispatcher->dispatch('retrofit.beforeSend', $beforeSendEvent);
        $request = $beforeSendEvent->getRequest();
        try {
            $response = $this->client->send($request);
        } catch (\Exception $exception) {
            $apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);
            $this->eventDispatcher->dispatch('retrofit.apiException', $apiExceptionEvent);
            $exception = $apiExceptionEvent->getException();
            throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);
        }
        $afterSendEvent = new \Tebru\Retrofit\Event\AfterSendEvent($request, $response);
        $this->eventDispatcher->dispatch('retrofit.afterSend', $afterSendEvent);
        $response = $afterSendEvent->getResponse();
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array());
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function partsChangeName($bar, $foo)
    {
        $requestUrl = $this->baseUrl . '/post';
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $body = http_build_query(array('foo' => $bar, 'bar' => $foo));
        $request = new \GuzzleHttp\Psr7\Request('POST', $requestUrl, $headers, $body);
        $beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);
        $this->eventDispatcher->dispatch('retrofit.beforeSend', $beforeSendEvent);
        $request = $beforeSendEvent->getRequest();
        try {
            $response = $this->client->send($request);
        } catch (\Exception $exception) {
            $apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);
            $this->eventDispatcher->dispatch('retrofit.apiException', $apiExceptionEvent);
            $exception = $apiExceptionEvent->getException();
            throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);
        }
        $afterSendEvent = new \Tebru\Retrofit\Event\AfterSendEvent($request, $response);
        $this->eventDispatcher->dispatch('retrofit.afterSend', $afterSendEvent);
        $response = $afterSendEvent->getResponse();
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array());
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function jsonBody(array $myBody)
    {
        $requestUrl = $this->baseUrl . '/post';
        $headers = array('Content-Type' => 'application/json');
        $body = json_encode($myBody);
        $request = new \GuzzleHttp\Psr7\Request('POST', $requestUrl, $headers, $body);
        $beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);
        $this->eventDispatcher->dispatch('retrofit.beforeSend', $beforeSendEvent);
        $request = $beforeSendEvent->getRequest();
        try {
            $response = $this->client->send($request);
        } catch (\Exception $exception) {
            $apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);
            $this->eventDispatcher->dispatch('retrofit.apiException', $apiExceptionEvent);
            $exception = $apiExceptionEvent->getException();
            throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);
        }
        $afterSendEvent = new \Tebru\Retrofit\Event\AfterSendEvent($request, $response);
        $this->eventDispatcher->dispatch('retrofit.afterSend', $afterSendEvent);
        $response = $afterSendEvent->getResponse();
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array());
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function objectJsonBody(\Tebru\Retrofit\Test\Mock\MockUser $user)
    {
        $requestUrl = $this->baseUrl . '/post';
        $headers = array('Content-Type' => 'application/json');
        $body = $this->serializer->serialize($user, 'json');
        $request = new \GuzzleHttp\Psr7\Request('POST', $requestUrl, $headers, $body);
        $beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);
        $this->eventDispatcher->dispatch('retrofit.beforeSend', $beforeSendEvent);
        $request = $beforeSendEvent->getRequest();
        try {
            $response = $this->client->send($request);
        } catch (\Exception $exception) {
            $apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);
            $this->eventDispatcher->dispatch('retrofit.apiException', $apiExceptionEvent);
            $exception = $apiExceptionEvent->getException();
            throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);
        }
        $afterSendEvent = new \Tebru\Retrofit\Event\AfterSendEvent($request, $response);
        $this->eventDispatcher->dispatch('retrofit.afterSend', $afterSendEvent);
        $response = $afterSendEvent->getResponse();
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array());
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function partsJsonBody($foo, $bar)
    {
        $requestUrl = $this->baseUrl . '/post';
        $headers = array('Content-Type' => 'application/json');
        $body = json_encode(array('foo' => $foo, 'bar' => $bar));
        $request = new \GuzzleHttp\Psr7\Request('POST', $requestUrl, $headers, $body);
        $beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);
        $this->eventDispatcher->dispatch('retrofit.beforeSend', $beforeSendEvent);
        $request = $beforeSendEvent->getRequest();
        try {
            $response = $this->client->send($request);
        } catch (\Exception $exception) {
            $apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);
            $this->eventDispatcher->dispatch('retrofit.apiException', $apiExceptionEvent);
            $exception = $apiExceptionEvent->getException();
            throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);
        }
        $afterSendEvent = new \Tebru\Retrofit\Event\AfterSendEvent($request, $response);
        $this->eventDispatcher->dispatch('retrofit.afterSend', $afterSendEvent);
        $response = $afterSendEvent->getResponse();
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array());
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function headerJsonBody($foo, array $bar)
    {
        $requestUrl = $this->baseUrl . '/post';
        $headers = array('foo' => $foo, 'Content-Type' => 'application/json');
        $body = json_encode($bar);
        $request = new \GuzzleHttp\Psr7\Request('POST', $requestUrl, $headers, $body);
        $beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);
        $this->eventDispatcher->dispatch('retrofit.beforeSend', $beforeSendEvent);
        $request = $beforeSendEvent->getRequest();
        try {
            $response = $this->client->send($request);
        } catch (\Exception $exception) {
            $apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);
            $this->eventDispatcher->dispatch('retrofit.apiException', $apiExceptionEvent);
            $exception = $apiExceptionEvent->getException();
            throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);
        }
        $afterSendEvent = new \Tebru\Retrofit\Event\AfterSendEvent($request, $response);
        $this->eventDispatcher->dispatch('retrofit.afterSend', $afterSendEvent);
        $response = $afterSendEvent->getResponse();
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array());
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function multipart(array $bar)
    {
        $requestUrl = $this->baseUrl . '/post';
        $headers = array('Content-Type' => 'multipart/form-data');
        $body = http_build_query($bar);
        $request = new \GuzzleHttp\Psr7\Request('POST', $requestUrl, $headers, $body);
        $beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);
        $this->eventDispatcher->dispatch('retrofit.beforeSend', $beforeSendEvent);
        $request = $beforeSendEvent->getRequest();
        try {
            $response = $this->client->send($request);
        } catch (\Exception $exception) {
            $apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);
            $this->eventDispatcher->dispatch('retrofit.apiException', $apiExceptionEvent);
            $exception = $apiExceptionEvent->getException();
            throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);
        }
        $afterSendEvent = new \Tebru\Retrofit\Event\AfterSendEvent($request, $response);
        $this->eventDispatcher->dispatch('retrofit.afterSend', $afterSendEvent);
        $response = $afterSendEvent->getResponse();
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array());
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
}
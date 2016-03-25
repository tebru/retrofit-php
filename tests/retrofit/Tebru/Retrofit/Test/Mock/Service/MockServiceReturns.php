<?php

namespace Tebru\Retrofit\Generated\Tebru\Retrofit\Test\Mock\Service;

class MockServiceReturns implements \Tebru\Retrofit\Test\Mock\Service\MockServiceReturns
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
    public function rawReturn()
    {
        $requestUrl = $this->baseUrl . '/get';
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $body = null;
        $request = new \GuzzleHttp\Psr7\Request('GET', $requestUrl, $headers, $body);
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
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'raw', $this->serializer, array('groups' => NULL, 'version' => NULL, 'serializeNull' => true, 'enableMaxDepthChecks' => true, 'attributes' => array(), 'depth' => 4));
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function arrayReturn()
    {
        $requestUrl = $this->baseUrl . '/get';
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $body = null;
        $request = new \GuzzleHttp\Psr7\Request('GET', $requestUrl, $headers, $body);
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
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array('groups' => NULL, 'version' => NULL, 'serializeNull' => true, 'enableMaxDepthChecks' => true, 'attributes' => array(), 'depth' => 4));
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function deserializedReturn()
    {
        $requestUrl = $this->baseUrl . '/get';
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $body = null;
        $request = new \GuzzleHttp\Psr7\Request('GET', $requestUrl, $headers, $body);
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
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'Tebru\\Retrofit\\Test\\Mock\\MockUser', $this->serializer, array('groups' => NULL, 'version' => NULL, 'serializeNull' => true, 'enableMaxDepthChecks' => true, 'attributes' => array(), 'depth' => 3));
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function responseReturnArray()
    {
        $requestUrl = $this->baseUrl . '/get';
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $body = null;
        $request = new \GuzzleHttp\Psr7\Request('GET', $requestUrl, $headers, $body);
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
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array('groups' => NULL, 'version' => NULL, 'serializeNull' => true, 'enableMaxDepthChecks' => true, 'attributes' => array(), 'depth' => 4));
        $return = $retrofitResponse;
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function responseReturnRaw()
    {
        $requestUrl = $this->baseUrl . '/get';
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $body = null;
        $request = new \GuzzleHttp\Psr7\Request('GET', $requestUrl, $headers, $body);
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
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'raw', $this->serializer, array('groups' => NULL, 'version' => NULL, 'serializeNull' => true, 'enableMaxDepthChecks' => true, 'attributes' => array(), 'depth' => 4));
        $return = $retrofitResponse;
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function responseReturnObject()
    {
        $requestUrl = $this->baseUrl . '/get';
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $body = null;
        $request = new \GuzzleHttp\Psr7\Request('GET', $requestUrl, $headers, $body);
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
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'Tebru\\Retrofit\\Test\\Mock\\MockUser', $this->serializer, array('groups' => NULL, 'version' => NULL, 'serializeNull' => true, 'enableMaxDepthChecks' => true, 'attributes' => array(), 'depth' => 4));
        $return = $retrofitResponse;
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
}
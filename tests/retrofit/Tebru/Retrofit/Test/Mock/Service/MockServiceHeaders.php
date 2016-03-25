<?php

namespace Tebru\Retrofit\Generated\Tebru\Retrofit\Test\Mock\Service;

class MockServiceHeaders implements \Tebru\Retrofit\Test\Mock\Service\MockServiceHeaders
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
    public function noMethodHeaders()
    {
        $requestUrl = $this->baseUrl . '/get';
        $headers = array('foo' => 'bar', 'baz' => 'buzz', 'Content-Type' => 'application/x-www-form-urlencoded');
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
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array());
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function oneMethodHeader($kit)
    {
        $requestUrl = $this->baseUrl . '/get';
        $headers = array('foo' => 'bar', 'baz' => 'buzz', 'kit' => $kit, 'Content-Type' => 'application/x-www-form-urlencoded');
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
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array());
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function headerChangeName($kat)
    {
        $requestUrl = $this->baseUrl . '/get';
        $headers = array('foo' => 'bar', 'baz' => 'buzz', 'kit' => $kat, 'Content-Type' => 'application/x-www-form-urlencoded');
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
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array());
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function headerOverwrite($foo, $kit)
    {
        $requestUrl = $this->baseUrl . '/get';
        $headers = array('foo' => $foo, 'baz' => 'buzz', 'kit' => $kit, 'Content-Type' => 'application/x-www-form-urlencoded');
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
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array());
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
}
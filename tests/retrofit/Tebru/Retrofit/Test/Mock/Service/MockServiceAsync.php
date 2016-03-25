<?php

namespace Tebru\Retrofit\Generated\Tebru\Retrofit\Test\Mock\Service;

class MockServiceAsync implements \Tebru\Retrofit\Test\Mock\Service\MockServiceAsync
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
    public function wait()
    {
        $this->client->wait();
    }
    public function asyncOptional(\Tebru\Retrofit\Http\Callback $callback = null)
    {
        $requestUrl = $this->baseUrl . '/get';
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $body = null;
        $request = new \GuzzleHttp\Psr7\Request('GET', $requestUrl, $headers, $body);
        $beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);
        $this->eventDispatcher->dispatch('retrofit.beforeSend', $beforeSendEvent);
        $request = $beforeSendEvent->getRequest();
        try {
            if ($callback !== null) {
                $response = $this->client->sendAsync($request, $callback);
            } else {
                $response = $this->client->send($request);
            }
        } catch (\Exception $exception) {
            $apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);
            $this->eventDispatcher->dispatch('retrofit.apiException', $apiExceptionEvent);
            $exception = $apiExceptionEvent->getException();
            throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);
        }
        $afterSendEvent = new \Tebru\Retrofit\Event\AfterSendEvent($request, $response);
        $this->eventDispatcher->dispatch('retrofit.afterSend', $afterSendEvent);
        $response = $afterSendEvent->getResponse();
        if ($callback !== null) {
            $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent(null);
            $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
            return $returnEvent->getReturn();
        }
        $retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'array', $this->serializer, array());
        $return = $retrofitResponse->body();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
    public function asyncNotOptional(\Tebru\Retrofit\Http\Callback $callback)
    {
        $requestUrl = $this->baseUrl . '/get';
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $body = null;
        $request = new \GuzzleHttp\Psr7\Request('GET', $requestUrl, $headers, $body);
        $beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);
        $this->eventDispatcher->dispatch('retrofit.beforeSend', $beforeSendEvent);
        $request = $beforeSendEvent->getRequest();
        try {
            $response = $this->client->sendAsync($request, $callback);
        } catch (\Exception $exception) {
            $apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);
            $this->eventDispatcher->dispatch('retrofit.apiException', $apiExceptionEvent);
            $exception = $apiExceptionEvent->getException();
            throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);
        }
        $afterSendEvent = new \Tebru\Retrofit\Event\AfterSendEvent($request, $response);
        $this->eventDispatcher->dispatch('retrofit.afterSend', $afterSendEvent);
        $response = $afterSendEvent->getResponse();
        $returnEvent = new \Tebru\Retrofit\Event\ReturnEvent(null);
        $this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
        return $returnEvent->getReturn();
    }
}
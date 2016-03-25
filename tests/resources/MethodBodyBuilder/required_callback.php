<?php

$requestUrl = $this->baseUrl . '/get';
$headers = array();
$body = null;
$request = new \GuzzleHttp\Psr7\Request('GET', $requestUrl, $headers, $body);
$this->logger->debug('Created Request', array('request' => array('method' => $request->getMethod(), 'uri' => rawurldecode((string) $request->getUri()), 'headers' => $request->getHeaders(), 'body' => (string) $request->getBody())));
$beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);
$this->eventDispatcher->dispatch('retrofit.beforeSend', $beforeSendEvent);
$request = $beforeSendEvent->getRequest();
try {
    $response = $this->client->sendAsync($request, $callback);
} catch (\Exception $exception) {
    $this->logger->error('Caught Exception', array('exception' => $exception));
    $apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);
    $this->eventDispatcher->dispatch('retrofit.apiException', $apiExceptionEvent);
    $exception = $apiExceptionEvent->getException();
    throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);
}
$this->logger->debug('API Response', array('response' => $response));
$afterSendEvent = new \Tebru\Retrofit\Event\AfterSendEvent($request, $response);
$this->eventDispatcher->dispatch('retrofit.afterSend', $afterSendEvent);
$response = $afterSendEvent->getResponse();
$returnEvent = new \Tebru\Retrofit\Event\ReturnEvent(null);
$this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
return $returnEvent->getReturn();

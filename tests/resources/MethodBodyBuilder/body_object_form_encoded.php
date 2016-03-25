<?php

$requestUrl = $this->baseUrl . '/path';
$headers = array();
$body = $this->serializer->serialize($body, 'json');
$body = json_decode($body, true);
$body = \Tebru\Retrofit\Generation\Manipulator\BodyManipulator::boolToString($body);
$body = http_build_query($body);
$request = new \GuzzleHttp\Psr7\Request('POST', $requestUrl, $headers, $body);
$this->logger->debug('Created Request', array('request' => array('method' => $request->getMethod(), 'uri' => rawurldecode((string) $request->getUri()), 'headers' => $request->getHeaders(), 'body' => (string) $request->getBody())));
$this->logger->info('Dispatching BeforeSendEvent');
$beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);
$this->eventDispatcher->dispatch('retrofit.beforeSend', $beforeSendEvent);
$request = $beforeSendEvent->getRequest();
try {
    $this->logger->info('Sending Synchronous Request');
    $response = $this->client->send($request);
} catch (\Exception $exception) {
    $this->logger->error('Caught Exception', array('exception' => $exception));
    $this->logger->info('Dispatching ApiExceptionEvent');
    $apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);
    $this->eventDispatcher->dispatch('retrofit.apiException', $apiExceptionEvent);
    $exception = $apiExceptionEvent->getException();
    throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);
}
$this->logger->debug('API Response', array('response' => $response));
$this->logger->info('Dispatching AfterSendEvent');
$afterSendEvent = new \Tebru\Retrofit\Event\AfterSendEvent($request, $response);
$this->eventDispatcher->dispatch('retrofit.afterSend', $afterSendEvent);
$response = $afterSendEvent->getResponse();
$retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'Tebru\\Retrofit\\Test\\Mock\\MockUser', $this->serializer, array());
$return = $retrofitResponse->body();
$this->logger->info('Dispatching ReturnEvent');
$returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
$this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
return $returnEvent->getReturn();

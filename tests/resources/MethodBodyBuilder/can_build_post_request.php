<?php

$requestUrl = $this->baseUrl . '/path';
$headers = array('Content-Type' => 'application/json');
$context = \JMS\Serializer\SerializationContext::create();
$context->setGroups(array('test' => 'group'));
$context->setVersion(1);
$context->setSerializeNull(1);
$context->enableMaxDepthChecks();
$context->setAttribute('foo', 'bar');
$body = $this->serializer->serialize($body, 'json', $context);
$request = new \GuzzleHttp\Psr7\Request('POST', $requestUrl, $headers, $body);
$this->logger->debug('Created Request', array('request' => array('method' => $request->getMethod(), 'uri' => rawurldecode((string) $request->getUri()), 'headers' => $request->getHeaders(), 'body' => (string) $request->getBody())));
$beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);
$this->eventDispatcher->dispatch('retrofit.beforeSend', $beforeSendEvent);
$request = $beforeSendEvent->getRequest();
try {
    $response = $this->client->send($request);
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
$retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'Tebru\\Retrofit\\Test\\Mock\\MockUser', $this->serializer, array('groups' => array('test' => 'group'), 'version' => 1, 'serializeNull' => true, 'enableMaxDepthChecks' => true, 'attributes' => array('foo' => 'bar'), 'depth' => 2));
$return = $retrofitResponse->body();
$returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);
$this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
return $returnEvent->getReturn();

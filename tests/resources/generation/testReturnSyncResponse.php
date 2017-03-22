<?php

$retrofitResponse = new \Tebru\Retrofit\Http\Response($response, 'Tebru\\Retrofit\\Test\\Mock\\MockUser', $this->serializer, array());
$return = $retrofitResponse;
$returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return, $request, $response);
$this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
return $returnEvent->getReturn();

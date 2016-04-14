<?php

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

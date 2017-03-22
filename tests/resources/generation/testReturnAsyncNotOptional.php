<?php

$returnEvent = new \Tebru\Retrofit\Event\ReturnEvent(null, $request, $response);
$this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
return $returnEvent->getReturn();

<?php

$returnEvent = new \Tebru\Retrofit\Event\ReturnEvent(null);
$this->eventDispatcher->dispatch('retrofit.return', $returnEvent);
return $returnEvent->getReturn();

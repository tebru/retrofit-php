<?php

$bodySerializationContext = array();
$bodyArray = $this->serializerAdapter->toArray($retrofitBody, $bodySerializationContext);
$bodyArray = \Tebru\Retrofit\Generation\Manipulator\QueryManipulator::boolToString($bodyArray);
$body = http_build_query($bodyArray);

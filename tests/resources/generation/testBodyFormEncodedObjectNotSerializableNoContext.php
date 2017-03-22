<?php

$bodySerializationContext = \JMS\Serializer\SerializationContext::create();
$bodyArray = $this->serializer->toArray($retrofitBody, $bodySerializationContext);
$bodyArray = \Tebru\Retrofit\Generation\Manipulator\QueryManipulator::boolToString($bodyArray);
$body = http_build_query($bodyArray);

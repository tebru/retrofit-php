<?php

$bodySerializationContext = \JMS\Serializer\SerializationContext::create();
$bodySerializationContext->setGroups(array(0 => 'group1', 1 => 'group2'));
$bodySerializationContext->setVersion(1);
$bodySerializationContext->setSerializeNull(true);
$bodySerializationContext->enableMaxDepthChecks();
$bodySerializationContext->setAttribute('foo', 'bar');
$bodyArray = $this->serializer->toArray($retrofitBody, $bodySerializationContext);
$bodyArray = \Tebru\Retrofit\Generation\Manipulator\QueryManipulator::boolToString($bodyArray);
$body = http_build_query($bodyArray);

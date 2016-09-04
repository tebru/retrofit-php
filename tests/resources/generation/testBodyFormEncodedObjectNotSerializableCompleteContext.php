<?php

$bodySerializationContext = array('groups' => array(0 => 'group1', 1 => 'group2'), 'version' => 1, 'serializeNull' => true, 'enableMaxDepthChecks' => true, 'attributes' => array('foo' => 'bar'));
$bodyArray = $this->serializerAdapter->toArray($retrofitBody, $bodySerializationContext);
$bodyArray = \Tebru\Retrofit\Generation\Manipulator\QueryManipulator::boolToString($bodyArray);
$body = http_build_query($bodyArray);

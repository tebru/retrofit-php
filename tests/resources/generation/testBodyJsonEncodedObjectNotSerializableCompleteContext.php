<?php

$bodySerializationContext = array('groups' => array(0 => 'group1', 1 => 'group2'), 'version' => 1, 'serializeNull' => true, 'enableMaxDepthChecks' => true, 'attributes' => array('foo' => 'bar'));
$body = $this->serializerAdapter->serialize($retrofitBody, $bodySerializationContext);

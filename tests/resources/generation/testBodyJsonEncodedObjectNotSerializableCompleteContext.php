<?php

$bodySerializationContext = \JMS\Serializer\SerializationContext::create();
$bodySerializationContext->setGroups(array(0 => 'group1', 1 => 'group2'));
$bodySerializationContext->setVersion(1);
$bodySerializationContext->setSerializeNull(true);
$bodySerializationContext->enableMaxDepthChecks();
$bodySerializationContext->setAttribute('foo', 'bar');
$body = $this->serializer->serialize($retrofitBody, 'json', $bodySerializationContext);

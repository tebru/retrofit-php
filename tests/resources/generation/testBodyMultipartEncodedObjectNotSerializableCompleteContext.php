<?php

$bodySerializationContext = \JMS\Serializer\SerializationContext::create();
$bodySerializationContext->setGroups(array(0 => 'group1', 1 => 'group2'));
$bodySerializationContext->setVersion(1);
$bodySerializationContext->setSerializeNull(true);
$bodySerializationContext->enableMaxDepthChecks();
$bodySerializationContext->setAttribute('foo', 'bar');
$bodyArray = $this->serializer->toArray($retrofitBody, $bodySerializationContext);
$bodyParts = array();
foreach ($bodyArray as $key => $value) {
    $file = null;
    if (is_resource($value)) {
        $file = $value;
    }
    if (is_string($value)) {
        $file = fopen($value, 'r');
    }
    if (!is_resource($file)) {
        throw new \LogicException('Expected resource or file path');
    }
    $bodyParts[] = array('name' => $key, 'contents' => $file);
}
$body = new \GuzzleHttp\Psr7\MultipartStream($bodyParts, 'fooboundary');

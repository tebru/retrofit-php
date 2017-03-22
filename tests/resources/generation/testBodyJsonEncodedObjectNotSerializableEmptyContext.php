<?php

$bodySerializationContext = \JMS\Serializer\SerializationContext::create();
$body = $this->serializer->serialize($retrofitBody, 'json', $bodySerializationContext);

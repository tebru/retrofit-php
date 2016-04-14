<?php

$queryArray = \Tebru\Retrofit\Generation\Manipulator\QueryManipulator::boolToString(array('foo' => 'bar'));
$queryString = http_build_query($queryArray);
$requestUrl = $this->baseUrl . '/get' . '?' . $queryString;

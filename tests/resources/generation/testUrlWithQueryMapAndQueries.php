<?php

$queryArray = \Tebru\Retrofit\Generation\Manipulator\QueryManipulator::boolToString(array('foo' => 'bar') + $retrofitQueryMap);
$queryString = http_build_query($queryArray);
$requestUrl = $this->baseUrl . '/get?' . $queryString;

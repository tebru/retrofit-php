<?php

$queryArray = \Tebru\Retrofit\Generation\Manipulator\QueryManipulator::boolToString($retrofitQueryMap);
$queryString = http_build_query($queryArray);
$requestUrl = $this->baseUrl . '/get?' . $queryString;

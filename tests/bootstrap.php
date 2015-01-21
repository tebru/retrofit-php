<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Tebru\Retrofit\Retrofit;
use Tebru\Retrofit\Test\Functional\Mock\MockService;

require_once __DIR__ . '/../vendor/autoload.php';

AnnotationRegistry::registerAutoloadNamespace('Tebru\Retrofit\Annotation', __DIR__ . '/../src');
AnnotationRegistry::registerAutoloadNamespace('JMS\Serializer\Annotation', __DIR__ . '/../vendor/jms/serializer/src');

$retrofit = new Retrofit(__DIR__ . '/../cache/tests');
$retrofit->registerService(MockService::class);
$retrofit->createCache();
$retrofit->load();

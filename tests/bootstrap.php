<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Tebru\Retrofit\Retrofit;
use Tebru\Retrofit\Test\Functional\Mock\MockService;

$loader = require __DIR__ . '/../vendor/autoload.php';
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

$retrofit = new Retrofit(__DIR__ . '/../cache/tests');
$retrofit->registerService(MockService::class);
$retrofit->createCache();
$retrofit->load();

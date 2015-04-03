<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Tebru\Retrofit\Retrofit;
use Tebru\Retrofit\Test\Functional\Mock\MockService;
use Tebru\Retrofit\Test\Functional\Mock\MockSimpleService;

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4('Tebru\\Retrofit\\Service\\', __DIR__ . '/../cache/tests/retrofit');
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

$retrofit = new Retrofit(__DIR__ . '/../cache/tests');
$retrofit->registerService(MockService::class);
$retrofit->registerService(MockSimpleService::class);
$retrofit->createCache();

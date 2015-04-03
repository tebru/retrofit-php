<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Tebru\Retrofit\Provider\GeneratedClassMetaDataProvider;
use Tebru\Retrofit\Retrofit;
use Tebru\Retrofit\Test\Mock\MockService;
use Tebru\Retrofit\Test\Mock\MockServiceHeaders;
use Tebru\Retrofit\Test\Mock\MockSimpleService;

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4(GeneratedClassMetaDataProvider::NAMESPACE_PREFIX . '\\', __DIR__ . '/../cache/tests/retrofit');
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

$retrofit = new Retrofit(__DIR__ . '/../cache/tests');
$retrofit->registerServices([MockService::class, MockSimpleService::class, MockServiceHeaders::class]);
$retrofit->createCache();

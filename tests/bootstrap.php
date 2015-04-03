<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

use Doctrine\Common\Annotations\AnnotationRegistry;
use Tebru\Retrofit\Provider\GeneratedClassMetaDataProvider;
use Tebru\Retrofit\Retrofit;
use Tebru\Retrofit\Test\Mock\MockDefaultParamTest;
use Tebru\Retrofit\Test\Mock\MockService;
use Tebru\Retrofit\Test\Mock\MockServiceHeaders;
use Tebru\Retrofit\Test\Mock\MockSimpleService;

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4(GeneratedClassMetaDataProvider::NAMESPACE_PREFIX . '\\', __DIR__ . '/../cache/tests/retrofit');
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

$retrofit = new Retrofit(__DIR__ . '/../cache/tests');
$retrofit->registerServices([MockService::class, MockSimpleService::class, MockServiceHeaders::class, MockDefaultParamTest::class]);
$retrofit->createCache();

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

use Doctrine\Common\Annotations\AnnotationRegistry;
use Tebru\Retrofit\Provider\GeneratedClassMetaDataProvider;

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4(GeneratedClassMetaDataProvider::NAMESPACE_PREFIX . '\\', __DIR__ . '/../cache/tests/retrofit');
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

define('TEST_DIR', __DIR__);

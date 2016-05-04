<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Feature\Context;

use Behat\Behat\Context\Context;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Tebru\Retrofit\Retrofit;
use Tebru\Retrofit\Test\Mock\ApiClient;

/**
 * Class SetupContext
 *
 * @author Nate Brunette <n@tebru.net>
 */
class SetupContext implements Context
{
    /**
     * @param BeforeSuiteScope $scope
     *
     * @BeforeSuite
     */
    public static function prepare(BeforeSuiteScope $scope)
    {
        define('PROJECT_ROOT', __DIR__ . '/../../..');
        $loader = require PROJECT_ROOT . '/vendor/autoload.php';
        $loader->addPsr4(Retrofit::NAMESPACE_PREFIX . '\\', PROJECT_ROOT . '/cache/tests/retrofit');
        AnnotationRegistry::registerLoader([$loader, 'loadClass']);

        $retrofit = Retrofit::builder()
            ->setCacheDir(PROJECT_ROOT . '/cache/tests')
            ->build();
        $retrofit->registerServices([ApiClient::class]);
        $retrofit->createCache();
    }
}

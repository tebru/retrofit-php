<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit\Internal\ProxyFactoryTest;

use stdClass;

/**
 * Interface ProxyFactoryTestClientNoReturnType
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface ProxyFactoryTestClientNoReturnType
{
    public function foo(stdClass $foo);
}

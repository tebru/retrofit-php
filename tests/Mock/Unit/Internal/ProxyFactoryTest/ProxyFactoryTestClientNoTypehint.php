<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit\Internal\ProxyFactoryTest;

use Tebru\Retrofit\Call;

/**
 * Interface ProxyFactoryTestClientNoTypehint
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface ProxyFactoryTestClientNoTypehint
{
    public function foo($foo): Call;
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Unit\Internal;

use LogicException;
use PHPUnit\Framework\TestCase;
use Tebru\PhpType\TypeToken;
use Tebru\Retrofit\Internal\CallAdapter\CallAdapterProvider;
use Tebru\Retrofit\Internal\CallAdapter\DefaultCallAdapterFactory;
use Tebru\Retrofit\Test\Mock\Unit\MockCall;

/**
 * Class CallAdapterTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class CallAdapterTest extends TestCase
{
    /**
     * @var CallAdapterProvider
     */
    private $callAdapterProvider;

    public function setUp(): void
    {
        $this->callAdapterProvider = new CallAdapterProvider([new DefaultCallAdapterFactory()]);
    }

    public function testAdaptDefaultCall()
    {
        $call = new MockCall();
        $adaptedCall = $this->callAdapterProvider->get(new TypeToken(MockCall::class))->adapt($call);

        self::assertSame($adaptedCall, $call);
    }

    public function testCallAdapterNotFound()
    {
        try {
            $this->callAdapterProvider->get(new TypeToken(self::class));
        } catch (LogicException $exception) {
            self::assertSame(
                'Retrofit: Could not get call adapter for type ' .
                'Tebru\Retrofit\Test\Unit\Internal\CallAdapterTest',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception not thrown');
    }
}

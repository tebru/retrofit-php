<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit\Internal\AnnotationProcessorTest;

use Psr\Http\Message\StreamInterface;
use Tebru\Retrofit\Call;

/**
 * Interface AnnotationProcessorTestMock
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface AnnotationProcessorTestMock
{
    public function foo(int $bar): Call;
    public function body(StreamInterface $bar): Call;
    public function noType($bar): Call;
}

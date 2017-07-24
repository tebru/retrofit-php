<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit\RetrofitTest;

use Tebru\AnnotationReader\AbstractAnnotation;

/**
 * Class RetrofitTestCustomAnnotation
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class RetrofitTestCustomAnnotation extends AbstractAnnotation
{
    protected function init(): void
    {
    }

}

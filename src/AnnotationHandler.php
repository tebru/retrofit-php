<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit;

use Tebru\AnnotationReader\AbstractAnnotation;

/**
 * Interface AnnotationHandler
 *
 * Implementations of this interface accept an annotation and manipulate the [@see ServiceMethodBuilder] based
 * on the value.
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface AnnotationHandler
{
    /**
     * Handle an annotation, mutating the [@see ServiceMethodBuilder] based on the value
     *
     * @param AbstractAnnotation $annotation The annotation to handle
     * @param ServiceMethodBuilder $serviceMethodBuilder Used to construct a [@see ServiceMethod]
     * @param Converter|StringConverter|RequestBodyConverter|null $converter Converter used to convert types before sending to service method
     * @param int|null $index The position of the parameter or null if annotation does not reference parameter
     * @return void
     */
    public function handle(
        AbstractAnnotation $annotation,
        ServiceMethodBuilder $serviceMethodBuilder,
        ?Converter $converter,
        ?int $index
    ): void;
}

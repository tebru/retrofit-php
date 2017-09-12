<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\AnnotationHandler;

use Tebru\AnnotationReader\AbstractAnnotation;
use Tebru\Retrofit\Annotation\Part;
use Tebru\Retrofit\AnnotationHandler;
use Tebru\Retrofit\Converter;
use Tebru\Retrofit\Internal\ParameterHandler\PartParamHandler;
use Tebru\Retrofit\RequestBodyConverter;
use Tebru\Retrofit\ServiceMethodBuilder;

/**
 * Class PartAnnotHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class PartAnnotHandler implements AnnotationHandler
{
    /**
     * Handle an annotation, mutating the [@see ServiceMethodBuilder] based on the value
     *
     * @param Part|AbstractAnnotation $annotation The annotation to handle
     * @param ServiceMethodBuilder $serviceMethodBuilder Used to construct a [@see ServiceMethod]
     * @param Converter|RequestBodyConverter $converter Converter used to convert types before sending to service method
     * @param int|null $index The position of the parameter or null if annotation does not reference parameter
     * @return void
     */
    public function handle(
        AbstractAnnotation $annotation,
        ServiceMethodBuilder $serviceMethodBuilder,
        ?Converter $converter,
        ?int $index
    ): void {
        $serviceMethodBuilder->setIsMultipart();
        $serviceMethodBuilder->addParameterHandler(
            $index,
            new PartParamHandler($converter, $annotation->getValue(), $annotation->getEncoding())
        );
    }
}

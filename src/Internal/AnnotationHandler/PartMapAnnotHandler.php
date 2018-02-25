<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\AnnotationHandler;

use InvalidArgumentException;
use Tebru\AnnotationReader\AbstractAnnotation;
use Tebru\Retrofit\Annotation\PartMap;
use Tebru\Retrofit\AnnotationHandler;
use Tebru\Retrofit\Converter;
use Tebru\Retrofit\Internal\ParameterHandler\PartMapParamHandler;
use Tebru\Retrofit\RequestBodyConverter;
use Tebru\Retrofit\ServiceMethodBuilder;

/**
 * Class PartMapAnnotHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class PartMapAnnotHandler implements AnnotationHandler
{
    /**
     * Add part map param handler
     *
     * @param PartMap|AbstractAnnotation $annotation The annotation to handle
     * @param ServiceMethodBuilder $serviceMethodBuilder Used to construct a [@see ServiceMethod]
     * @param Converter|RequestBodyConverter $converter Converter used to convert types before sending to service method
     * @param int|null $index The position of the parameter or null if annotation does not reference parameter
     * @return void
     * @throws \InvalidArgumentException
     */
    public function handle(
        AbstractAnnotation $annotation,
        ServiceMethodBuilder $serviceMethodBuilder,
        ?Converter $converter,
        ?int $index
    ): void {
        if (!$annotation instanceof PartMap) {
            throw new InvalidArgumentException('Retrofit: Annotation must be a PartMap');
        }

        if (!$converter instanceof RequestBodyConverter) {
            throw new InvalidArgumentException(sprintf(
                'Retrofit: Converter must be a RequestBodyConverter, %s found',
                \gettype($converter)
            ));
        }

        $serviceMethodBuilder->setIsMultipart();
        $serviceMethodBuilder->addParameterHandler(
            $index,
            new PartMapParamHandler($converter, $annotation->getEncoding())
        );
    }
}

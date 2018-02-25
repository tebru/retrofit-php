<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\AnnotationHandler;

use InvalidArgumentException;
use Tebru\AnnotationReader\AbstractAnnotation;
use Tebru\Retrofit\Annotation\Encodable;
use Tebru\Retrofit\Annotation\QueryMap;
use Tebru\Retrofit\AnnotationHandler;
use Tebru\Retrofit\Converter;
use Tebru\Retrofit\Internal\ParameterHandler\QueryMapParamHandler;
use Tebru\Retrofit\ServiceMethodBuilder;
use Tebru\Retrofit\StringConverter;

/**
 * Class QueryMapAnnotHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class QueryMapAnnotHandler implements AnnotationHandler
{
    /**
     * Add query map param handler
     *
     * @param QueryMap|AbstractAnnotation $annotation The annotation to handle
     * @param ServiceMethodBuilder $serviceMethodBuilder Used to construct a [@see ServiceMethod]
     * @param Converter|StringConverter $converter Converter used to convert types before sending to service method
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
        if (!$annotation instanceof Encodable) {
            throw new InvalidArgumentException('Retrofit: Annotation must be encodable');
        }

        if (!$converter instanceof StringConverter) {
            throw new InvalidArgumentException(sprintf(
                'Retrofit: Converter must be a StringConverter, %s found',
                \gettype($converter)
            ));
        }

        $serviceMethodBuilder->addParameterHandler(
            $index,
            new QueryMapParamHandler($converter, $annotation->isEncoded())
        );
    }
}

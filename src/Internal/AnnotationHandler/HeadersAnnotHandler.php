<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\AnnotationHandler;

use InvalidArgumentException;
use Tebru\AnnotationReader\AbstractAnnotation;
use Tebru\Retrofit\AnnotationHandler;
use Tebru\Retrofit\Converter;
use Tebru\Retrofit\ServiceMethodBuilder;

/**
 * Class HeadersAnnotHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class HeadersAnnotHandler implements AnnotationHandler
{
    /**
     * Set each header to request
     *
     * @param AbstractAnnotation $annotation The annotation to handle
     * @param ServiceMethodBuilder $serviceMethodBuilder Used to construct a [@see ServiceMethod]
     * @param Converter|null $converter Converter used to convert types before sending to service method
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
        if ($converter !== null) {
            throw new InvalidArgumentException(sprintf(
                'Retrofit: Converter must be null, %s found',
                \gettype($converter)
            ));
        }

        /** @var string[] $headerList */
        $headerList = $annotation->getValue();
        foreach ($headerList as $name => $header) {
            $serviceMethodBuilder->addHeader($name, $header);
        }
    }
}

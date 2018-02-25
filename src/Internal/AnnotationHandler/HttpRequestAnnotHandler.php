<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\AnnotationHandler;

use InvalidArgumentException;
use Tebru\AnnotationReader\AbstractAnnotation;
use Tebru\Retrofit\Annotation\HttpRequest;
use Tebru\Retrofit\AnnotationHandler;
use Tebru\Retrofit\Converter;
use Tebru\Retrofit\ServiceMethodBuilder;

/**
 * Class HttpRequestAnnotHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class HttpRequestAnnotHandler implements AnnotationHandler
{
    /**
     * Sets the request method, uri, and whether or not the request contains a body
     *
     * @param HttpRequest|AbstractAnnotation $annotation The annotation to handle
     * @param ServiceMethodBuilder $serviceMethodBuilder Used to construct a [@see ServiceMethod]
     * @param Converter|null $converter Converter used to convert types before sending to service method
     * @param int|null $index The position of the parameter or null if annotation does not reference parameter
     * @return void
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    public function handle(
        AbstractAnnotation $annotation,
        ServiceMethodBuilder $serviceMethodBuilder,
        ?Converter $converter,
        ?int $index
    ): void {
        if (!$annotation instanceof HttpRequest) {
            throw new InvalidArgumentException('Retrofit: Annotation must be an HttpRequest');
        }

        if ($converter !== null) {
            throw new InvalidArgumentException(sprintf(
                'Retrofit: Converter must be null, %s found',
                \gettype($converter)
            ));
        }

        $uri = $annotation->getValue();

        $serviceMethodBuilder->setMethod($annotation->getType());
        $serviceMethodBuilder->setPath($uri);

        if (!$annotation->hasBody()) {
            $serviceMethodBuilder->setHasBody($annotation->hasBody());
        }
    }
}

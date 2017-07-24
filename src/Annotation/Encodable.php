<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Annotation;

use Tebru\Retrofit\StringConverter;

/**
 * Abstract class that adds an 'encoded' boolean key to annotations to
 * represent data that is already encoded or not.
 *
 * @author Nate Brunette <n@tebru.net>
 */
abstract class Encodable extends ParameterAnnotation
{
    /**
     * The values are already encoded
     *
     * @var bool
     */
    private $encoded;

    /**
     * Initialize annotation data
     */
    protected function init(): void
    {
        parent::init();

        $this->encoded = $this->data['encoded'] ?? false;
    }

    /**
     * Returns true if the values are already encoded
     *
     * @return bool
     */
    public function isEncoded(): bool
    {
        return $this->encoded;
    }

    /**
     * Return the converter interface class
     *
     * Can be one of RequestBodyConverter, ResponseBodyConverter, or StringConverter
     *
     * @return string
     */
    public function converterType(): ?string
    {
        return StringConverter::class;
    }
}

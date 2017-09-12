<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Annotation;

use Tebru\Retrofit\Http\MultipartBody;
use Tebru\Retrofit\RequestBodyConverter;

/**
 * Represents a collection of @Part annotations
 *
 * The default value specifies the variable name in the method signature.
 *
 * Any iterable may be passed as an argument.
 *
 * If the item is not a [@see MultipartBody], keys of the map will be the field
 * names, and the values will be handled exactly the same as as @Part. Otherwise,
 * all values of the MultipartBody will be used.
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class PartMap extends ParameterAnnotation
{
    /**
     * How the multipart request is encoded
     *
     * @var string
     */
    private $encoding;

    /**
     * Initialize annotation data
     */
    protected function init(): void
    {
        parent::init();

        $this->encoding = $this->data['encoding'] ?? 'binary';
    }

    /**
     * Get the encoding type
     *
     * @return string
     */
    public function getEncoding(): string
    {
        return $this->encoding;
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
        return RequestBodyConverter::class;
    }

    /**
     * Returns true if multiple annotations of this type are allowed
     *
     * @return bool
     */
    public function allowMultiple(): bool
    {
        return true;
    }
}

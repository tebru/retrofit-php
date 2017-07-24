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
 * Denotes a single part of a multipart request.
 *
 * The default value represents the part name. Passing a [@see MultipartBody] will use the values from
 * that object, otherwise the value will be converted to a stream and added to the request.
 *
 * Use the 'encoding' key to override the default 'binary' encoding.
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class Part extends ParameterAnnotation
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
     * Whether or not multiple annotations of this type can
     * be added to a method
     *
     * @return bool
     */
    public function allowMultiple(): bool
    {
        return true;
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
}

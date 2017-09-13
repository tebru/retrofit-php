<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Http;

use Psr\Http\Message\StreamInterface;

use function GuzzleHttp\Psr7\stream_for;

/**
 * Class MultipartBody
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class MultipartBody
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var StreamInterface
     */
    private $contents;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $filename;

    /**
     * Constructor
     *
     * @param string $name
     * @param mixed $contents
     * @param array[] $headers
     * @param null|string $filename
     */
    public function __construct(string $name, $contents, array $headers = [], ?string $filename = null)
    {
        $this->name = $name;
        $this->contents = stream_for($contents);
        $this->headers = $headers;
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return StreamInterface
     */
    public function getContents(): StreamInterface
    {
        return $this->contents;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal;

use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use LogicException;
use Psr\Http\Message\StreamInterface;

/**
 * Class RequestBuilder
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class RequestBuilder
{
    /**
     * The request method
     *
     * @var string
     */
    private $method;

    /**
     * The request [@see Uri] object
     *
     * @var Uri
     */
    private $uri;

    /**
     * An array of query strings that can be appended together
     *
     * @var array
     */
    private $queries = [];

    /**
     * An array of headers in PSR-7 format
     *
     * @var array
     */
    private $headers;

    /**
     * The request body
     *
     * @var StreamInterface
     */
    private $body;

    /**
     * An array of request body fields that can be appended together
     *
     * @var array
     */
    private $fields = [];

    /**
     * An array of arrays of multipart parts, each with name, content, headers, and filename
     *
     * @var array[]
     */
    private $parts = [];

    /**
     * Constructor
     *
     * @param string $method
     * @param string $baseUrl
     * @param string $uri
     * @param array $headers
     */
    public function __construct(string $method, string $baseUrl, string $uri, array $headers)
    {
        $this->method = $method;
        $this->uri = new Uri($baseUrl.$uri);
        $this->headers = $headers;
    }

    /**
     * Set the uri base url
     *
     * @param string $value
     */
    public function setBaseUrl(string $value): void
    {
        $uri = new Uri($value);
        $this->uri = $this->uri
            ->withScheme($uri->getScheme())
            ->withHost($uri->getHost())
            ->withPort($uri->getPort());
    }

    /**
     * Replace a url path placeholder with a value
     *
     * @param string $name
     * @param string $value
     */
    public function replacePath(string $name, string $value): void
    {
        $path = rawurldecode($this->uri->getPath());
        $path = str_replace(sprintf('{%s}', $name), $value, $path);
        $this->uri = $this->uri->withPath($path);
    }

    /**
     * Add a query string; if encoded, decodes to be encoded later
     *
     * @param string $name
     * @param string $value
     * @param bool $encoded
     */
    public function addQuery(string $name, string $value, bool $encoded): void
    {
        $name = rawurlencode($name);
        if ($encoded === false) {
            $value = rawurlencode($value);
        }

        $this->queries[] = $name.'='.$value;
    }

    /**
     * Adds a query string without value; if encoded, decodes to be encoded later
     *
     * @param string $value
     * @param bool $encoded
     */
    public function addQueryName(string $value, bool $encoded): void
    {
        if ($encoded === false) {
            $value = rawurlencode($value);
        }

        $this->queries[] = $value;
    }

    /**
     * Add a header in PSR-7 format
     *
     * @param string $name
     * @param string $value
     */
    public function addHeader(string $name, string $value): void
    {
        $name = strtolower($name);
        $this->headers[$name][] = $value;
    }

    /**
     * Set the request body
     *
     * @param StreamInterface $body
     */
    public function setBody(StreamInterface $body): void
    {
        $this->body = $body;
    }

    /**
     * Add a field; if not encoded, encodes first
     *
     * @param string $name
     * @param string $value
     * @param bool $encoded
     */
    public function addField(string $name, string $value, bool $encoded): void
    {
        $name = rawurlencode($name);
        if ($encoded === false) {
            $value = rawurlencode($value);
        }

        $this->fields[] = $name.'='.$value;
    }

    /**
     * Add a multipart part
     *
     * @param string $name
     * @param StreamInterface $contents
     * @param array $headers
     * @param null|string $filename
     */
    public function addPart(string $name, StreamInterface $contents, array $headers = [], ?string $filename = null): void
    {
        $this->parts[] = [
            'name' => $name,
            'contents' => $contents,
            'headers' => $headers,
            'filename' => $filename,
        ];
    }

    /**
     * Create a PSR-7 request
     *
     * @return Request
     * @throws \LogicException
     */
    public function build(): Request
    {
        $uri = $this->uri;
        if ($this->queries !== []) {
            $query = implode('&', $this->queries);
            $uri = $this->uri->getQuery() === ''
                ? $this->uri->withQuery($query)
                : $this->uri->withQuery($query.'&'.$this->uri->getQuery());
        }

        if ($this->fields !== []) {
            if ($this->body !== null) {
                throw new LogicException('Retrofit: Cannot mix @Field and @Body annotations.');
            }

            $this->body = Psr7\stream_for(implode('&', $this->fields));
        }

        if ($this->parts !== []) {
            if ($this->body !== null) {
                throw new LogicException('Retrofit: Cannot mix @Part and @Body annotations.');
            }

            $this->body = new MultipartStream($this->parts);
        }

        return new Request($this->method, $uri, $this->headers, $this->body);
    }
}

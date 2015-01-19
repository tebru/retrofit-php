<?php
/**
 * File HttpRequest.php
 */

namespace Tebru\Retrofit;

use Exception;

/**
 * Class HttpRequest
 *
 * Parent class for Http request annotations
 *
 * @author Nate Brunette <n@tebru.net>
 */
abstract class HttpRequest
{
    /**
     * Url path
     *
     * @var string $path
     */
    private $path;

    /**
     * Constructor
     *
     * @param array $params
     * @throws Exception if path is not set
     */
    public function __construct(array $params)
    {
        if (!isset($params['value'])) {
            throw new Exception('Url path not set on annotation');
        }

        $this->path = $params['value'];
    }

    /**
     * Returns the type of the annotation (get, post, put, etc)
     *
     * @return string
     */
    abstract public function getType();

    /**
     * Get the url path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}

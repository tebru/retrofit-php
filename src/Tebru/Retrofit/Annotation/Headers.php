<?php
/**
 * File Headers.php
 */
namespace Tebru\Retrofit\Annotation;

use Exception;
use LogicException;

/**
 * Class Headers
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 */
class Headers
{
    /**
     * @var array $headers
     */
    private $headers = [];

    /**
     * Constructor
     *
     * @param array $params
     * @throws Exception
     */
    public function __construct(array $params)
    {
        if (!isset($params['value'])) {
            throw new Exception('Headers are not set');
        }

        // convert to array
        if (!is_array($params['value'])) {
            $params['value'] = [$params['value']];
        }

        // loop through each string and break on ':'
        foreach ($params['value'] as $header) {
            $pos = strpos($header, ':');

            if (false === $pos) {
                throw new LogicException('Header in an incorrect format.  Expected <Name: value>');
            }

            $name = trim(substr($header, 0, $pos));
            $value = trim(substr($header, $pos + 1));

            $this->headers[$name] = $value;
        }
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}

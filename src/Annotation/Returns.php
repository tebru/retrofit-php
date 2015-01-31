<?php
/**
 * File Returns.php
 */

namespace Tebru\Retrofit\Annotation;

use Exception;

/**
 * Class Returns
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 */
class Returns
{
    /**
     * @var string $return
     */
    private $return;

    /**
     * Constructor
     *
     * @param array $params
     * @throws Exception
     */
    public function __construct(array $params)
    {
        if (!isset($params['value'])) {
            throw new Exception('Return parameter name not set');
        }

        $this->return = $params['value'];
    }

    /**
     * @return string
     */
    public function getReturn()
    {
        return $this->return;
    }
}

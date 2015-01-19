<?php
/**
 * File HEAD.php
 */

namespace Tebru\Retrofit\Annotation;

use Tebru\Retrofit\HttpRequest;

/**
 * Class HEAD
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 */
class HEAD extends HttpRequest
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'head';
    }
}

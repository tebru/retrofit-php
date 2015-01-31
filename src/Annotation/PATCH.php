<?php
/**
 * File PATCH.php
 */

namespace Tebru\Retrofit\Annotation;

use Tebru\Retrofit\HttpRequest;

/**
 * Class PATCH
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 */
class PATCH extends HttpRequest
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'patch';
    }
}

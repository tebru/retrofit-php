<?php
/**
 * File DELETE.php
 */

namespace Tebru\Retrofit\Annotation;

use Tebru\Retrofit\HttpRequest;

/**
 * Class DELETE
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 */
class DELETE extends HttpRequest
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'delete';
    }
}

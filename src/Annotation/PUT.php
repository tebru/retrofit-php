<?php
/**
 * File PUT.php
 */

namespace Tebru\Retrofit\Annotation;

/**
 * Class PUT
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 */
class PUT extends HttpRequest
{
    public function getType()
    {
        return 'put';
    }
}

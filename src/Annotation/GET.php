<?php
/**
 * File GET.php
 */

namespace Tebru\Retrofit\Annotation;

/**
 * Class GET
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 */
class GET extends HttpRequest
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'get';
    }
}

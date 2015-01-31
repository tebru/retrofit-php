<?php
/**
 * File POST.php
 */

namespace Tebru\Retrofit\Annotation;

/**
 * Class POST
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 */
class POST extends HttpRequest
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'post';
    }
}

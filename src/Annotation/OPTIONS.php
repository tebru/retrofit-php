<?php
/**
 * File OPTIONS.php
 */

namespace Tebru\Retrofit\Annotation;

/**
 * Class OPTIONS
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 */
class OPTIONS extends HttpRequest
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'options';
    }
}

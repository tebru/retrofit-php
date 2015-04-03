<?php
/**
 * File HeadersHandler.php
 */

namespace Tebru\Retrofit\Handler;

use Tebru\Retrofit\Annotation\Headers;
use Tebru\Retrofit\Model\Method;

/**
 * Class HeadersHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class HeadersHandler implements AnnotationHandler
{
    /**
     * @param Method $method
     * @param Headers $annotation
     * @return null
     */
    public function handle(Method $method, $annotation)
    {
        $method->addHeaders($annotation->getHeaders());
    }
}
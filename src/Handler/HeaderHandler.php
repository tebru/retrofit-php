<?php
/**
 * File HeaderHandler.php
 */

namespace Tebru\Retrofit\Handler;

use Tebru\Retrofit\Annotation\Header;
use Tebru\Retrofit\Model\Method;

/**
 * Class HeaderHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class HeaderHandler implements AnnotationHandler
{
    /**
     * @param Method $method
     * @param Header $annotation
     * @return null
     */
    public function handle(Method $method, $annotation)
    {
        $method->addHeaders([$annotation->getKey() => $annotation->getValue()]);
    }
}
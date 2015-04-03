<?php
/**
 * File PartHandler.php
 */

namespace Tebru\Retrofit\Handler;

use Tebru\Retrofit\Annotation\Part;
use Tebru\Retrofit\Model\Method;

/**
 * Class PartHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class PartHandler implements AnnotationHandler
{
    /**
     * @param Method $method
     * @param Part $annotation
     * @return null
     */
    public function handle(Method $method, $annotation)
    {
        $method->addParts([$annotation->getKey() => $annotation->getValue()]);
    }
}
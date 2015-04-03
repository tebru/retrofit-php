<?php
/**
 * File BodyHandler.php
 */

namespace Tebru\Retrofit\Handler;

use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Model\Method;

/**
 * Class BodyHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class BodyHandler implements AnnotationHandler
{
    /**
     * @param Method $method
     * @param Body $annotation
     * @return null
     */
    public function handle(Method $method, $annotation)
    {
        $method->setOptions(['body' => $annotation->getValue()]);
    }
}
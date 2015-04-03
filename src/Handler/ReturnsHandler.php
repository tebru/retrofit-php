<?php
/**
 * File ReturnsHandler.php
 */

namespace Tebru\Retrofit\Handler;

use Tebru\Retrofit\Annotation\Returns;
use Tebru\Retrofit\Model\Method;

/**
 * Class ReturnsHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ReturnsHandler implements AnnotationHandler
{
    /**
     * @param Method $method
     * @param Returns $annotation
     * @return null
     */
    public function handle(Method $method, $annotation)
    {
        $method->setReturn($annotation->getReturn());
    }
}
<?php
/**
 * File JsonBodyHandler.php
 */

namespace Tebru\Retrofit\Handler;

use Tebru\Retrofit\Annotation\JsonBody;
use Tebru\Retrofit\Model\Method;

/**
 * Class JsonBodyHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class JsonBodyHandler implements AnnotationHandler
{
    /**
     * @param Method $method
     * @param JsonBody $annotation
     * @return null
     */
    public function handle(Method $method, $annotation)
    {
        $method->setJsonBody(true);
    }
}
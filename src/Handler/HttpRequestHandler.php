<?php
/**
 * File HttpRequestHandler.php
 */

namespace Tebru\Retrofit\Handler;

use Tebru\Retrofit\Annotation\HttpRequest;
use Tebru\Retrofit\Model\Method;

/**
 * Class HttpRequestHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class HttpRequestHandler implements AnnotationHandler
{
    /**
     * @param Method $method
     * @param HttpRequest $annotation
     * @return null
     */
    public function handle(Method $method, $annotation)
    {
        $method->setType($annotation->getType());
        $method->setPath($annotation->getPath());
        $method->addQueries($annotation->getQueries());
    }
}
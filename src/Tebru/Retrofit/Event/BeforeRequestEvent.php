<?php
/**
 * File BeforeRequestEvent.php
 */

namespace Tebru\Retrofit\Event;

use GuzzleHttp\Message\RequestInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class BeforeRequestEvent
 *
 * @author Nate Brunette <n@tebru.net>
 */
class BeforeRequestEvent extends Event
{
    /**
     * @var RequestInterface $request
     */
    private $request;

    /**
     * Constructor
     *
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }
}

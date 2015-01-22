<?php
/**
 * File AfterRequestEvent.php
 */

namespace Tebru\Retrofit\Event;

use GuzzleHttp\Message\ResponseInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class AfterRequestEvent
 *
 * @author Nate Brunette <n@tebru.net>
 */
class AfterRequestEvent extends Event
{
    /**
     * @var ResponseInterface $response
     */
    private $response;

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Subscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tebru\Retrofit\Event\AfterSendEvent;
use Tebru\Retrofit\Event\ApiExceptionEvent;

/**
 * Class LogSubscriber
 *
 * @author Nate Brunette <n@tebru.net>
 */
class LogSubscriber implements EventSubscriberInterface
{
    /**
     * PSR Logger
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            AfterSendEvent::NAME => 'onAfterSend',
            ApiExceptionEvent::NAME => 'onApiException',
        ];
    }

    /**
     * Log the request and response if it exists
     *
     * @param AfterSendEvent $event
     */
    public function onAfterSend(AfterSendEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        $log['request'] = [
            'method' => $request->getMethod(),
            'uri' => rawurldecode((string) $request->getUri()),
            'headers' => $request->getHeaders(),
            'body' => (string) $request->getBody(),
        ];

        if (null !== $response) {
            $log['response'] = [
                'statusCode' => $response->getStatusCode(),
                'reasonPhrase' => $response->getReasonPhrase(),
                'body' => (string) $response->getBody(),
                'headers' => $response->getHeaders(),
            ];
        }

        $this->logger->debug('Sent Request', $log);
    }

    /**
     * Log an api exception with the request
     *
     * @param ApiExceptionEvent $event
     */
    public function onApiException(ApiExceptionEvent $event)
    {
        $request = $event->getRequest();
        $exception = $event->getException();

        $this->logger->error('Request Exception', [
            'request' => [
                'method' => $request->getMethod(),
                'uri' => rawurldecode((string) $request->getUri()),
                'headers' => $request->getHeaders(),
                'body' => (string) $request->getBody(),
            ],
            'exception' => $exception,
        ]);
    }
}

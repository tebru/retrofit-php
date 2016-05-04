<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Handler;

use LogicException;
use Tebru\Retrofit\Generation\Handler;
use Tebru\Retrofit\Generation\HandlerContext;

/**
 * Class UrlHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class UrlHandler implements Handler
{
    /**
     * Handle request url code generation
     *
     * @param HandlerContext $context
     * @return null
     * @throws LogicException
     */
    public function __invoke(HandlerContext $context)
    {
        $baseUrl = $context->annotations()->getBaseUrl() ?: '$this->baseUrl';
        $queryMap = $context->annotations()->getQueryMap();
        $queries = $context->annotations()->getQueries();
        $uri = $context->annotations()->getRequestUri();

        // if there aren't queries or a query map, just set request url
        if (null === $queryMap && null === $queries) {
            $context->body()->add('$requestUrl = %s . "%s";', $baseUrl, $uri);

            return null;
        }

        // if there's a query map, check to see if there are also queries
        if (null !== $queryMap) {
            // if we have regular queries, add them to the query builder
            if (null !== $queries) {
                $queryArray = $context->printer()->printArray($queries);
                $context->body()->add('$queryArray = \Tebru\Retrofit\Generation\Manipulator\QueryManipulator::boolToString(%s + %s);', $queryArray, $queryMap);
                $context->body()->add('$queryString = http_build_query($queryArray);');
            } else {
                $context->body()->add('$queryArray = \Tebru\Retrofit\Generation\Manipulator\QueryManipulator::boolToString(%s);', $queryMap);
                $context->body()->add('$queryString = http_build_query($queryArray);');
            }

            $context->body()->add('$requestUrl = %s . "%s?" . $queryString;', $baseUrl, $uri);

            return null;
        }

        // add queries to request url
        $queryArray = $context->printer()->printArray($queries);
        $context->body()->add('$queryArray = \Tebru\Retrofit\Generation\Manipulator\QueryManipulator::boolToString(%s);', $queryArray);
        $context->body()->add('$queryString = http_build_query($queryArray);');
        $context->body()->add('$requestUrl = %s . "%s" . "?" . $queryString;', $baseUrl, $uri);
    }
}

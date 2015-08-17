<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Handler;

use Tebru\Retrofit\Annotation\HttpRequest;
use Tebru\Retrofit\Annotation\Query;
use Tebru\Retrofit\Annotation\QueryMap;

/**
 * Class RequestUrlHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RequestUrlHandler extends Handler
{
    /** {@inheritdoc} */
    public function handle()
    {
        /** @var HttpRequest $requestAnnotation */
        $requestAnnotation = $this->annotations->get(HttpRequest::NAME);

        $this->methodBodyBuilder->setRequestMethod($requestAnnotation->getType());
        $this->methodBodyBuilder->setUri($requestAnnotation->getPath());

        $this->setQueries($requestAnnotation);
        $this->setQueryMap();
    }

    /**
     * Set queries if annotation exists, otherwise just use request uri queries
     *
     * @param HttpRequest $requestAnnotation
     */
    private function setQueries(HttpRequest $requestAnnotation)
    {
        $queries = $requestAnnotation->getQueries();

        if ($this->annotations->exists(Query::NAME)) {
            /** @var Query $queryAnnotation */
            foreach ($this->annotations->get(Query::NAME) as $queryAnnotation) {
                $queries[$queryAnnotation->getRequestKey()] = $queryAnnotation->getVariable();
            }

        }

        $this->methodBodyBuilder->setQueries($queries);
    }

    /**
     * Set query map if annotation exists
     *
     * @return null
     */
    private function setQueryMap()
    {
        if (!$this->annotations->exists(QueryMap::NAME)) {
            return null;
        }

        /** @var QueryMap $queryMapAnnotation */
        $queryMapAnnotation = $this->annotations->get(QueryMap::NAME);
        $this->methodBodyBuilder->setQueryMap($queryMapAnnotation->getVariable());
    }
}

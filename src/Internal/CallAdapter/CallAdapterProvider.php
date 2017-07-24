<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\CallAdapter;

use LogicException;
use Tebru\PhpType\TypeToken;
use Tebru\Retrofit\CallAdapter;
use Tebru\Retrofit\CallAdapterFactory;

/**
 * Class CallAdapterProvider
 *
 * Returns a [@see CallAdapterFactory] that can handle a given type
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class CallAdapterProvider
{
    /**
     * @var CallAdapterFactory[]
     */
    private $callAdapterFactories;

    /**
     * Constructor
     *
     * @param CallAdapterFactory[] $callAdapterFactories
     */
    public function __construct(array $callAdapterFactories)
    {
        $this->callAdapterFactories = $callAdapterFactories;
    }

    /**
     * Given a type, find the first available [@see CallAdapterFactory] and return it
     *
     * @param TypeToken $type
     * @return CallAdapter
     * @throws \LogicException
     */
    public function get(TypeToken $type): CallAdapter
    {
        foreach ($this->callAdapterFactories as $callAdapterFactory) {
            if ($callAdapterFactory->supports($type)) {
                return $callAdapterFactory->create($type);
            }
        }

        throw new LogicException(sprintf('Retrofit: Could not get call adapter for type %s', $type));
    }
}

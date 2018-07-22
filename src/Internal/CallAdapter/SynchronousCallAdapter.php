<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\CallAdapter;

use Tebru\PhpType\TypeToken;
use Tebru\Retrofit\Call;
use Tebru\Retrofit\CallAdapter;
use Tebru\Retrofit\CallAdapterFactory;

/**
 * Class SynchronousCallAdapter
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class SynchronousCallAdapter implements CallAdapter, CallAdapterFactory
{
    /**
     * Accepts a [@see Call] and converts it to the appropriate type
     *
     * @param Call $call
     * @return mixed
     */
    public function adapt(Call $call)
    {
        $response = $call->execute();

        if (!$response->isSuccessful()) {

        }

        return $response->body();
    }

    /**
     * Returns true if the factory supports this type
     *
     * @param TypeToken $type
     * @return bool
     */
    public function supports(TypeToken $type): bool
    {
        // support any type that is not a call
        return !$type->isA(Call::class);
    }

    /**
     * Create a new factory from type
     *
     * @param TypeToken $type
     * @return CallAdapter
     */
    public function create(TypeToken $type): CallAdapter
    {
        return new self();
    }
}

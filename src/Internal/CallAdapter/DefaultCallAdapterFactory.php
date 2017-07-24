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
 * Class DefaultCallAdapterFactory
 *
 * Only supports [@see Call] instances
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class DefaultCallAdapterFactory implements CallAdapterFactory
{
    /**
     * Returns true if the factory supports this type
     *
     * @param TypeToken $type
     * @return bool
     */
    public function supports(TypeToken $type): bool
    {
        return $type->isA(Call::class);
    }

    /**
     * Create a new factory from type
     *
     * @param TypeToken $type
     * @return CallAdapter
     */
    public function create(TypeToken $type): CallAdapter
    {
        return new DefaultCallAdapter();
    }
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit;

use Tebru\PhpType\TypeToken;

/**
 * Interface CallAdapterFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface CallAdapterFactory
{
    /**
     * Returns true if the factory supports this type
     *
     * @param TypeToken $type
     * @return bool
     */
    public function supports(TypeToken $type): bool;

    /**
     * Create a new factory from type
     *
     * @param TypeToken $type
     * @return CallAdapter
     */
    public function create(TypeToken $type): CallAdapter;
}

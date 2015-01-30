<?php
/**
 * File RestAdapterProvider.php 
 */

namespace Tebru\Retrofit;

use Tebru\Retrofit\Adapter\RestAdapter;

/**
 * Interface RestAdapterProvider
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface RestAdapterProvider
{
    /**
     * Return an instance of a configured rest adapter
     *
     * @see \Tebru\Retrofit\Adapter\Builder
     * @return RestAdapter
     */
    public function getRestAdapter();
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit\Internal\ProxyFactoryTest;

use Tebru\Retrofit\Internal\Filesystem;

/**
 * Class ProxyFactoryTestFilesystem
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ProxyFactoryTestFilesystem extends Filesystem
{
    public $makeDirectory = true;
    public $put = true;

    public $directory;
    public $filename;
    public $contents;

    public function makeDirectory(string $pathname, int $mode = 0777, bool $recursive = false, $context = null): bool
    {
        $this->directory = $pathname;
        return $this->makeDirectory;
    }

    public function put(string $filename, string $contents): bool
    {
        $this->filename = $filename;
        $this->contents = $contents;
        return $this->put;
    }

}

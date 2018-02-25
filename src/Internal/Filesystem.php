<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal;

/**
 * A light wrapper around filesystem commands
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Filesystem
{
    /**
     * Wraps the php mkdir() function, but defaults to recursive directory creation
     *
     * @param string $pathname
     * @param int $mode
     * @param bool $recursive
     * @return bool
     */
    public function makeDirectory(string $pathname, int $mode = 0777, bool $recursive = true): bool
    {
        return !(!@mkdir($pathname, $mode, $recursive) && !is_dir($pathname));
    }

    /**
     * Write contents to file
     *
     * @param string $filename
     * @param string $contents
     * @return bool
     */
    public function put(string $filename, string $contents): bool
    {
        $written = file_put_contents($filename, $contents);

        return !($written === 0);
    }
}

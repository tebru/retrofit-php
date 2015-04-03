<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Finder;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

/**
 * Class ServiceResolver
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ServiceResolver
{
    /**
     * Find all services given a source directory
     *
     * @param string $srcDir
     * @return array
     */
    public function findServices($srcDir)
    {
        $directory = new RecursiveDirectoryIterator($srcDir);
        $iterator = new RecursiveIteratorIterator($directory);
        $files = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

        $services = [];
        foreach ($files as $file) {
            $regex = '/Tebru\\\\Retrofit\\\\Annotation/';
            $fileString = file_get_contents($file[0]);
            $matchesFound = preg_match($regex, $fileString);

            if (!$matchesFound) {
                continue;
            }

            $namespaceRegex = '/^namespace\s+([\w\\\\]+)/m';
            $interfaceRegex = '/^interface\s+([\w\\\\]+)[\s{\n]?/m';
            $namespaceMatchesFound = preg_match($namespaceRegex, $fileString, $namespaceMatches);
            $interfaceMatchesFound = preg_match($interfaceRegex, $fileString, $interfaceMatches);

            if (!$interfaceMatchesFound) {
                continue;
            }

            $className = '';

            if ($namespaceMatchesFound) {
                $className .= '\\' . $namespaceMatches[1];
            }

            $className .= '\\' . $interfaceMatches[1];

            $services[] = $className;
        }

        return $services;
    }
}

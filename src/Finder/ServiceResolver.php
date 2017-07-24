<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

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
    const ANNOTATION_REGEX = '/Tebru\\\\Retrofit\\\\Annotation/';
    const FILE_REGEX = '/^.+\.php$/i';
    const INTERFACE_REGEX = '/^interface\s+([\w\\\\]+)[\s{\n]?/m';
    const NAMESPACE_REGEX = '/^namespace\s+([\w\\\\]+)/m';
    
    /**
     * Find all services given a source directory
     *
     * @param string $srcDir
     * @return string[]
     */
    public function findServices(string $srcDir): array
    {
        $directory = new RecursiveDirectoryIterator($srcDir);
        $iterator = new RecursiveIteratorIterator($directory);
        $files = new RegexIterator($iterator, self::FILE_REGEX, RecursiveRegexIterator::GET_MATCH);

        $services = [];
        foreach ($files as $file) {
            $fileString = file_get_contents($file[0]);
            
            $annotationMatchesFound = preg_match(self::ANNOTATION_REGEX, $fileString);

            if (!$annotationMatchesFound) {
                continue;
            }

            $interfaceMatchesFound = preg_match(self::INTERFACE_REGEX, $fileString, $interfaceMatches);

            if (!$interfaceMatchesFound) {
                continue;
            }
            
            $namespaceMatchesFound = preg_match(self::NAMESPACE_REGEX, $fileString, $namespaceMatches);

            $className = '';

            if ($namespaceMatchesFound) {
                $className .= $namespaceMatches[1];
            }

            $className .= '\\' . $interfaceMatches[1];

            $services[] = $className;
        }

        return $services;
    }
}

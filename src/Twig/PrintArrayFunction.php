<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Twig;

/**
 * Class PrintArrayFunction
 *
 * Allows php arrays to be printed in twig
 *
 * @author Nate Brunette <n@tebru.net>
 */
class PrintArrayFunction
{
    /**
     * Callable
     *
     * @param $array
     * @return string
     */
    public function __invoke($array)
    {
        // remove newlines, slashes, and spaces
        $array = str_replace("\n", '', var_export($array, true));
        $array = stripslashes($array);
        $array = str_replace(' ', '', $array);

        // converts to array literal notation
        $array = str_replace('array', '', $array);
        $array = str_replace('(', '[', $array);
        $array = str_replace(')', ']', $array);

        // add spaces
        $array = str_replace('=>', ' => ', $array);
        $array = str_replace(',', ', ', $array);

        // remove trailing comma
        $array = str_replace(', ]', ']', $array);

        // remove quotes from variables
        $regex = '(\'\$(?:\w)+\')';
        preg_match_all($regex, $array, $matches);

        if (isset($matches[0])) {
            foreach ($matches[0] as $match) {
                $array = str_replace($match, str_replace('\'', '', $match), $array);
            }
        }

        // remove indexes
        $regex = '/\d+\s=>\s/';
        $array = preg_replace($regex, '', $array);

        return $array;
    }
}

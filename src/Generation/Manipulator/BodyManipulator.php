<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Manipulator;

/**
 * Class BodyManipulator
 *
 * @author Nate Brunette <n@tebru.net>
 */
class BodyManipulator
{
    /**
     * Convert booleans to string so that they're not passed as 0 and 1
     *
     * @param array $elements
     * @return array
     */
    public static function boolToString(array $elements)
    {
        foreach ($elements as $key => $element) {
            if (is_array($element)) {
                $elements[$key] = self::boolToString($element);
            }

            if (is_bool($element)) {
                $elements[$key] = (true === $element) ? 'true' : 'false';
            }
        }

        return $elements;
    }
}

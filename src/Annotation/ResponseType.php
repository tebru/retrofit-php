<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation;

use Tebru;
use Tebru\Dynamo\Annotation\DynamoAnnotation;

/**
 * Class ResponseType
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class ResponseType implements DynamoAnnotation
{
    const NAME = 'response_type';

    /**
     * @var string
     */
    private $type;

    /**
     * Constructor
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        Tebru\assertThat(isset($params['value']), 'An argument was not passed to a "%s" annotation.', get_class($this));

        $this->type = $params['value'];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * The name of the annotation or class of annotations
     *
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * Whether or not multiple annotations of this type can
     * be added to a method
     *
     * @return bool
     */
    public function allowMultiple()
    {
        return false;
    }
}

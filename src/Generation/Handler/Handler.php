<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Handler;

use Tebru;
use Tebru\Dynamo\Collection\AnnotationCollection;
use Tebru\Dynamo\Model\MethodModel;
use Tebru\Retrofit\Generation\Builder\MethodBodyBuilder;

/**
 * Class Handler
 *
 * @author Nate Brunette <n@tebru.net>
 */
abstract class Handler
{
    /**
     * @var MethodModel
     */
    protected $methodModel;
    
    /**
     * @var MethodBodyBuilder
     */
    protected $methodBodyBuilder;
    
    /**
     * @var AnnotationCollection
     */
    protected $annotations;

    /**
     * @param MethodModel $methodModel
     * @param MethodBodyBuilder $methodBodyBuilder
     * @param AnnotationCollection $annotations
     */
    public function __construct(MethodModel $methodModel, MethodBodyBuilder $methodBodyBuilder, AnnotationCollection $annotations)
    {
        $this->methodModel = $methodModel;
        $this->methodBodyBuilder = $methodBodyBuilder;
        $this->annotations = $annotations;
    }

    /**
     * Handle the annotation
     *
     * @return null
     */
    abstract public function handle();
}

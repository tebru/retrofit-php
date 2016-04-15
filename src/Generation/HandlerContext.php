<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation;

use Tebru\Dynamo\Model\Body;
use Tebru\Retrofit\Generation\Printer\ArrayPrinter;
use Tebru\Retrofit\Generation\Provider\AnnotationProvider;

/**
 * Class HandlerContext
 *
 * @author Nate Brunette <n@tebru.net>
 */
class HandlerContext
{
    /**
     * @var AnnotationProvider
     */
    private $annotationProvider;

    /**
     * @var Body
     */
    private $methodBody;

    /**
     * @var ArrayPrinter
     */
    private $printer;

    /**
     * Constructor
     *
     * @param AnnotationProvider $annotationProvider
     * @param Body $methodBody
     * @param ArrayPrinter $printer
     */
    public function __construct(AnnotationProvider $annotationProvider, Body $methodBody, ArrayPrinter $printer)
    {
        $this->annotationProvider = $annotationProvider;
        $this->methodBody = $methodBody;
        $this->printer = $printer;
    }

    /**
     * @return AnnotationProvider
     */
    public function annotations()
    {
        return $this->annotationProvider;
    }

    /**
     * @return Body
     */
    public function body()
    {
        return $this->methodBody;
    }

    /**
     * @return ArrayPrinter
     */
    public function printer()
    {
        return $this->printer;
    }
}

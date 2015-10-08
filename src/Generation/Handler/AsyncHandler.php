<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Handler;

use Tebru\Dynamo\Model\ParameterModel;
use Tebru\Retrofit\Exception\RetrofitException;

/**
 * Class AsyncHandler
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */
class AsyncHandler extends Handler
{
    /** {@inheritdoc} */
    public function handle()
    {
        $callback = $this->getCallback();

        if ($callback === null) {
            return null;
        }

        $reflectionClass = new \ReflectionClass($this->methodModel->getClassModel()->getInterface());

        if (!in_array('Tebru\Retrofit\Http\AsyncAware', $reflectionClass->getInterfaceNames())) {
            throw new RetrofitException('Interfaces using async methods must implement the "AsyncAware" class');
        }

        $this->methodBodyBuilder->setCallback('$' . $callback->getName());
        $this->methodBodyBuilder->setIsCallbackOptional($callback->isOptional());
    }

    /**
     * Return callback parameter
     *
     * @return null|ParameterModel
     */
    private function getCallback()
    {
        $parameters = array_reverse($this->methodModel->getParameters());

        /** @var ParameterModel $parameter */
        foreach ($parameters as $parameter) {
            if ($parameter->getTypeHint() === '\Tebru\Retrofit\Http\Callback') {
                return $parameter;
            }
        }

        return null;
    }
}

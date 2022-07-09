<?php

namespace Psrearick\Containers\Actions;

use Psrearick\Containers\Services\ContainerItemParameters;
use Psrearick\Containers\Services\ContainerItemRequest;

class InvokeUpdateParameterActions
{
    public function execute(object $object, ContainerItemRequest $request) : ?ContainerItemParameters
    {
        if (method_exists($object, 'updateParametersAction')) {
            return $object->updateParametersAction($request);
        }

        return null;
    }
}

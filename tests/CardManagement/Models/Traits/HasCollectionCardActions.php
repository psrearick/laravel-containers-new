<?php

namespace Psrearick\Containers\Tests\CardManagement\Models\Traits;

use Psrearick\Containers\Services\ContainerItemParameters;
use Psrearick\Containers\Services\ContainerItemRequest;

trait HasCollectionCardActions
{
    public function updateParametersAction(ContainerItemRequest $request) : ContainerItemParameters
    {
        if ($request->getAction() === 'add') {
            return $this->addCardToCollection($request);
        }

        if ($request->getAction() === 'remove') {
            return $this->removeCardFromCollection($request);
        }

        if ($request->getAction() === 'update') {
            return $this->updateParameterInCollection($request);
        }

        return $request->getParameters();
    }

    protected function addCardToCollection(ContainerItemRequest $request) : ContainerItemParameters
    {
        return $request->getParameters();
    }

    protected function removeCardFromCollection(ContainerItemRequest $request) : ContainerItemParameters
    {
        return $request->getParameters();
    }

    protected function updateParameterInCollection(ContainerItemRequest $request) : ContainerItemParameters
    {
        return $request->getParameters();
    }
}

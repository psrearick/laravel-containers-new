<?php

namespace Psrearick\Containers\Tests\CardManagement\Models\Traits;

use Psrearick\Containers\Services\ContainerItemRequest;

trait HasPropagatingActions
{
    abstract public function addContainerItem(ContainerItemRequest $request) : void;

    public function propagateAction(ContainerItemRequest $request) : void
    {
        if ($request->getAction() === 'add') {
            $this->addContainerItem($request);
        }

        if ($request->getAction() === 'remove') {
            $this->removeContainerItem($request);
        }

        if ($request->getAction() === 'update') {
            $this->updateContainerItem($request);
        }
    }

    abstract public function removeContainerItem(ContainerItemRequest $request) : void;

    abstract public function updateContainerItem(ContainerItemRequest $request) : void;
}

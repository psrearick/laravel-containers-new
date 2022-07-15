<?php

namespace Psrearick\Containers\Actions;

use Psrearick\Containers\Containers;
use Psrearick\Containers\Contracts\ContainerContract;
use Psrearick\Containers\Contracts\ItemContract;
use Psrearick\Containers\Services\ContainerItemRequest;

class PropagateActions
{
    public function execute(ContainerContract|ItemContract $object, ContainerItemRequest $request) : void
    {
        $containerParents = collect([]);
        if (class_implements($object, ItemContract::class)) {
            if (method_exists($object, 'propagateAction')) {
                $object->propagateAction($request);
            }

            $containerParents = app(Containers::class)->getParents($object);
        }

        $containerParents->each(function (ContainerContract $container) use ($request) {
            $this->execute($container, $request);
        });
    }
}

<?php

namespace Psrearick\Containers\Actions;

use Psrearick\Containers\Containers;
use Psrearick\Containers\Contracts\ContainerContract;
use Psrearick\Containers\Contracts\ItemContract;
use Psrearick\Containers\Models\ContainerItem;
use Psrearick\Containers\Services\ContainerItemParameters;
use Psrearick\Containers\Services\ContainerItemRequest;

class UpdateItemInContainer
{
    public function execute(ItemContract $item, ContainerContract|ItemContract $container, ContainerItemParameters $parameters) : void
    {
        $currentParameters = app(Containers::class)->getParameters($item, $container);

        $request = new ContainerItemRequest(
            'update',
            $container,
            $item,
            $parameters,
            null,
            $currentParameters
        );

        $parameters = app(InvokeUpdateParameterActions::class)->execute($item, $request) ?? $parameters;
        $request->setParameters($parameters);

        $parameters = app(InvokeUpdateParameterActions::class)->execute($container, $request) ?? $parameters;
        $request->setParameters($parameters);

        $currentParameters->replaceAll($parameters->getAll());
        $request->setCurrentParameters($currentParameters);

        ContainerItem::updateOrCreate([
            'container_id'   => $container->id,
            'container_type' => get_class($container),
            'item_id'        => $item->id,
            'item_type'      => get_class($item),
        ], [
            'parameters'     => $currentParameters->getAll(),
        ]);

        app(PropagateActions::class)->execute($container, $request);
    }
}

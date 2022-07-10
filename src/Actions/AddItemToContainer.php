<?php

namespace Psrearick\Containers\Actions;

use Psrearick\Containers\Containers;
use Psrearick\Containers\Contracts\ContainerContract;
use Psrearick\Containers\Contracts\ItemContract;
use Psrearick\Containers\Models\ContainerItem;
use Psrearick\Containers\Services\ContainerItemParameters;
use Psrearick\Containers\Services\ContainerItemRequest;

class AddItemToContainer
{
    public function execute(
        ItemContract $item,
        ContainerContract $container,
        ContainerItemParameters $parameters
    ) : void {
        if (! $parameters->get('quantity')) {
            return;
        }

        $currentParameters = app(Containers::class)->getParameters($item, $container);

        $request = new ContainerItemRequest(
            'add',
            $container,
            $item,
            $parameters,
            null,
            $currentParameters
        );

        $parameters = app(InvokeUpdateParameterActions::class)->execute($item, $request) ?? $parameters;
        $request->setParameters($parameters);

        $parameters =
            (app(InvokeUpdateParameterActions::class)->execute($container, $request) ?? $parameters)
            ->set('quantity', ($currentParameters->get('quantity') ?? 0) + $parameters->get('quantity'));

        ContainerItem::updateOrCreate([
            'container_id'   => $container->id,
            'container_type' => get_class($container),
            'item_id'        => $item->id,
            'item_type'      => get_class($item),
        ], [
            'parameters'     => $parameters->getAll(),
        ]);
    }
}

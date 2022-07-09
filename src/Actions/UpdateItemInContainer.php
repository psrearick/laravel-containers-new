<?php

namespace Psrearick\Containers\Actions;

use JsonException;
use Psrearick\Containers\Containers;
use Psrearick\Containers\Contracts\ContainerContract;
use Psrearick\Containers\Contracts\ItemContract;
use Psrearick\Containers\Models\ContainerItem;
use Psrearick\Containers\Services\ContainerItemParameters;
use Psrearick\Containers\Services\ContainerItemRequest;

class UpdateItemInContainer
{
    /**
     * @throws JsonException
     */
    public function execute(ItemContract $item, ContainerContract $container, ContainerItemParameters $parameters) : void
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
        $parameters = app(InvokeUpdateParameterActions::class)->execute($container, $request) ?? $parameters;

        foreach ($parameters->getAll() as $key => $value) {
            $currentParameters->set($key, $value);
        }

        ContainerItem::updateOrCreate([
            'container_id'   => $container->id,
            'container_type' => get_class($container),
            'item_id'        => $item->id,
            'item_type'      => get_class($item),
        ], [
            'parameters'     => json_encode($currentParameters->getAll(), JSON_THROW_ON_ERROR),
        ]);
    }
}

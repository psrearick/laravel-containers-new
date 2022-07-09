<?php

namespace Psrearick\Containers\Actions;

use JsonException;
use Psrearick\Containers\Containers;
use Psrearick\Containers\Contracts\ContainerContract;
use Psrearick\Containers\Contracts\ItemContract;
use Psrearick\Containers\Models\ContainerItem;
use Psrearick\Containers\Services\ContainerItemParameters;

class AddItemToContainer
{
    /**
     * @throws JsonException
     */
    public function execute(
        ItemContract $item,
        ContainerContract $container,
        ContainerItemParameters $parameters
    ) : void {
        if (! $parameters->get('quantity')) {
            return;
        }

        $currentParameters = app(Containers::class)->getParameters($item, $container);
        $parameters->set('quantity', ($currentParameters->get('quantity') ?? 0) + $parameters->get('quantity'));

        ContainerItem::updateOrCreate([
            'container_id'   => $container->id,
            'container_type' => get_class($container),
            'item_id'        => $item->id,
            'item_type'      => get_class($item),
        ], [
            'parameters'     => json_encode($parameters->getAll(), JSON_THROW_ON_ERROR),
        ]);
    }
}

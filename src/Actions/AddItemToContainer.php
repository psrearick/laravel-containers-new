<?php

namespace Psrearick\Containers\Actions;

use JsonException;
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

        ContainerItem::create([
            'container_id'   => $container->id,
            'container_type' => get_class($container),
            'item_id'        => $item->id,
            'item_type'      => get_class($item),
            'quantity'       => $parameters->get('quantity'),
            'parameters'     => json_encode($parameters->getAll(), JSON_THROW_ON_ERROR),
        ]);
    }
}

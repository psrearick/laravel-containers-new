<?php

namespace Psrearick\Containers;

use JsonException;
use Psrearick\Containers\Models\ContainerItem;
use Psrearick\Containers\Services\ContainerItemParameters;
use Psrearick\Containers\Contracts\ItemContract as Item;
use Psrearick\Containers\Contracts\ContainerContract as Container;

class Containers
{
    /**
     * @throws JsonException
     */
    public function addItemToContainer(Item $item, Container $container, ContainerItemParameters $parameters) : void
    {
       ContainerItem::create([
           'container_id'   => $container->id,
           'container_type' => get_class($container),
           'item_id'        => $item->id,
           'item_type'      => get_class($item),
           'parameters'     => json_encode($parameters->getAll(), JSON_THROW_ON_ERROR),
       ]);
    }

    /**
     * @throws JsonException
     */
    public function getParameters(Item $item, Container $container) : ContainerItemParameters
    {
        $containerItem = ContainerItem::query()
            ->where('container_id', $container->id)
            ->where('container_type', get_class($container))
            ->where('item_id', $item->id)
            ->where('item_type', get_class($item))
            ->first();

        $parameters = app(ContainerItemParameters::class);

        if ($containerItem) {
            $parameters->setAll(json_decode($containerItem->parameters, true, 512, JSON_THROW_ON_ERROR));
        }

        return $parameters;
    }
}

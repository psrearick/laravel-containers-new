<?php

namespace Psrearick\Containers;

use JsonException;
use Psrearick\Containers\Actions\AddItemToContainer;
use Psrearick\Containers\Actions\GetContainerItem;
use Psrearick\Containers\Actions\GetContainerItemParameters;
use Psrearick\Containers\Actions\RemoveItemFromContainer;
use Psrearick\Containers\Actions\UpdateItemInContainer;
use Psrearick\Containers\Contracts\ContainerContract;
use Psrearick\Containers\Contracts\ItemContract;
use Psrearick\Containers\Models\ContainerItem;
use Psrearick\Containers\Services\ContainerItemParameters;

class Containers
{
    /**
     * @throws JsonException
     */
    public function addItemToContainer(
        ItemContract $item,
        ContainerContract $container,
        ContainerItemParameters $parameters
    ) : void {
        app(AddItemToContainer::class)->execute($item, $container, $parameters);
    }

    public function getContainerItem(ItemContract $item, ContainerContract $container) : ?ContainerItem
    {
        return app(GetContainerItem::class)->execute($item, $container);
    }

    /**
     * @throws JsonException
     */
    public function getParameters(ItemContract $item, ContainerContract $container) : ContainerItemParameters
    {
        return app(GetContainerItemParameters::class)->execute($item, $container);
    }

    /**
     * @throws JsonException
     */
    public function removeItemFromContainer(
        ItemContract $item,
        ContainerContract $container,
        ContainerItemParameters $parameters
    ) : void {
        app(RemoveItemFromContainer::class)->execute($item, $container, $parameters);
    }

    public function updateItemInContainer(
        ItemContract $item,
        ContainerContract $container,
        ContainerItemParameters $parameters
    ) : void {
        app(UpdateItemInContainer::class)->execute($item, $container, $parameters);
    }
}

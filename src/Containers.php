<?php

namespace Psrearick\Containers;

use Psrearick\Containers\Actions\AddItemToContainer;
use Psrearick\Containers\Actions\GetContainerItem;
use Psrearick\Containers\Actions\GetContainerItemParameters;
use Psrearick\Containers\Actions\MoveContainer;
use Psrearick\Containers\Actions\MoveItemToContainer;
use Psrearick\Containers\Actions\NestContainer;
use Psrearick\Containers\Actions\RemoveItemFromContainer;
use Psrearick\Containers\Actions\UnnestContainer;
use Psrearick\Containers\Actions\UpdateItemInContainer;
use Psrearick\Containers\Contracts\ContainerContract;
use Psrearick\Containers\Contracts\ItemContract;
use Psrearick\Containers\Models\ContainerItem;
use Psrearick\Containers\Services\ContainerItemParameters;

class Containers
{
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

    public function getContainerItems(ContainerContract $container, string $itemClass) : array
    {
        return ContainerItem::query()
            ->where('container_id', $container->id)
            ->where('container_type', get_class($container))
            ->where('item_type', $itemClass)
            ->get()
            ->toArray();
    }

    public function getParameters(ItemContract $item, ContainerContract $container) : ContainerItemParameters
    {
        return app(GetContainerItemParameters::class)->execute($item, $container);
    }

    public function moveContainer(ContainerContract $child, ContainerContract $parent) : void
    {
        app(MoveContainer::class)->execute($child, $parent);
    }

    public function moveItemToContainer(
        ItemContract $item,
        ContainerContract $source,
        ContainerContract $destination,
        ContainerItemParameters $parameters
    ) : void {
        app(MoveItemToContainer::class)->execute($item, $source, $destination, $parameters);
    }

    public function nestContainer(ContainerContract $child, ContainerContract $parent) : void
    {
        app(NestContainer::class)->execute($child, $parent);
    }

    public function removeItemFromContainer(
        ItemContract $item,
        ContainerContract $container,
        ContainerItemParameters $parameters
    ) : void {
        app(RemoveItemFromContainer::class)->execute($item, $container, $parameters);
    }

    public function unnestContainer(ContainerContract $child) : void
    {
        app(UnnestContainer::class)->execute($child);
    }

    public function updateItemInContainer(
        ItemContract $item,
        ContainerContract $container,
        ContainerItemParameters $parameters
    ) : void {
        app(UpdateItemInContainer::class)->execute($item, $container, $parameters);
    }
}

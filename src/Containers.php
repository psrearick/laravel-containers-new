<?php

namespace Psrearick\Containers;

use Illuminate\Support\Collection;
use Psrearick\Containers\Actions\AddItemToContainer;
use Psrearick\Containers\Actions\GetContainerItem;
use Psrearick\Containers\Actions\GetContainerItemParameters;
use Psrearick\Containers\Actions\GetParents;
use Psrearick\Containers\Actions\MoveContainer;
use Psrearick\Containers\Actions\MoveItemToContainer;
use Psrearick\Containers\Actions\NestContainer;
use Psrearick\Containers\Actions\RemoveItemFromContainer;
use Psrearick\Containers\Actions\UnnestContainer;
use Psrearick\Containers\Actions\UpdateItemInContainer;
use Psrearick\Containers\Contracts\ContainerContract;
use Psrearick\Containers\Contracts\ItemContract;
use Psrearick\Containers\Contracts\SingletonContract;
use Psrearick\Containers\Models\ContainerItem;
use Psrearick\Containers\Services\ContainerItemParameters;

class Containers
{
    public function addItemToContainer(
        ItemContract $item,
        ContainerContract $container,
        ?ContainerItemParameters $parameters = null
    ) : void {
        if (($item instanceof SingletonContract) && in_array(get_class($container), $item->getSingletonContainers(), true)) {
            $this->addSingletonItemToContainer($item, $container, $parameters);

            return;
        }

        $parameters = $parameters ?? new ContainerItemParameters();

        app(AddItemToContainer::class)->execute($item, $container, $parameters);
    }

    public function addSingletonItemToContainer(
        ItemContract $item,
        ContainerContract $container,
        ?ContainerItemParameters $parameters = null
    ) : void {
        $currentParameters = $this->getParameters($item, $container);

        if ($currentParameters->has('quantity') && $currentParameters->get('quantity') > 0) {
            return;
        }

        $parameters = $parameters ?? new ContainerItemParameters();
        $parameters->set('quantity', 1);

        app(AddItemToContainer::class)->execute($item, $container, $parameters);
    }

    public function getChildContainerItem(ContainerContract $child, ContainerContract $parent) : ?ContainerItem
    {
        return $this->getContainerItem($child, $parent);
    }

    public function getChildrenContainers(ContainerContract $container, string $childClass) : array
    {
        return $this->getContainerItems($container, $childClass);
    }

    public function getContainerItem(ItemContract|ContainerContract $item, ContainerContract $container) : ?ContainerItem
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

    public function getParents(ItemContract $item) : Collection
    {
        return app(GetParents::class)->execute($item);
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
        ?ContainerItemParameters $parameters = null
    ) : void {
        if (($item instanceof SingletonContract) && in_array(get_class($container), $item->getSingletonContainers(), true)) {
            $this->removeSingletonItemFromContainer($item, $container, $parameters);

            return;
        }

        $parameters = $parameters ?? new ContainerItemParameters();
        app(RemoveItemFromContainer::class)->execute($item, $container, $parameters);
    }

    public function removeSingletonItemFromContainer(
        ItemContract $item,
        ContainerContract $container,
        ?ContainerItemParameters $parameters = null
    ) : void {
        $currentParameters = $this->getParameters($item, $container);

        if ($currentParameters->has('quantity') && $currentParameters->get('quantity') === 0) {
            return;
        }

        if (! $currentParameters->has('quantity')) {
            return;
        }

        $parameters = $parameters ?? new ContainerItemParameters();
        $parameters->set('quantity', -1);

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

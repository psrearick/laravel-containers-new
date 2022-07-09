<?php

namespace Psrearick\Containers\Actions;

use Psrearick\Containers\Contracts\ContainerContract;
use Psrearick\Containers\Contracts\ItemContract;
use Psrearick\Containers\Models\ContainerItem;

class GetContainerItem
{
    public function execute(ItemContract $item, ContainerContract $container) : ?ContainerItem
    {
        return ContainerItem::where([
            'container_id'   => $container->id,
            'container_type' => get_class($container),
            'item_id'        => $item->id,
            'item_type'      => get_class($item),
        ])->first();
    }
}

<?php

namespace Psrearick\Containers\Actions;

use Illuminate\Support\Collection;
use Psrearick\Containers\Contracts\ItemContract;
use Psrearick\Containers\Models\ContainerItem;

class GetParents
{
    public function execute(ItemContract $item) : Collection
    {
        return ContainerItem::where([
            'item_id'        => $item->id,
            'item_type'      => get_class($item),
        ])
            ->get()
            ->map(function (ContainerItem $containerItem) {
                return app($containerItem->container_type)->find($containerItem->container_id);
            });
    }
}

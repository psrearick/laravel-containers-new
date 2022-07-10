<?php

namespace Psrearick\Containers\Actions;

use Psrearick\Containers\Contracts\ContainerContract;
use Psrearick\Containers\Models\ContainerItem;

class NestContainer
{
    public function execute(ContainerContract $child, ContainerContract $parent) : void
    {
        ContainerItem::create([
            'container_id'   => $parent->id,
            'container_type' => get_class($parent),
            'item_id'        => $child->id,
            'item_type'      => get_class($child),
        ]);
    }
}

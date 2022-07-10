<?php

namespace Psrearick\Containers\Actions;

use Psrearick\Containers\Contracts\ContainerContract;
use Psrearick\Containers\Models\ContainerItem;

class UnnestContainer
{
    public function execute(ContainerContract $child) : void
    {
        ContainerItem::where([
            'item_id'   => $child->id,
            'item_type' => get_class($child),
        ])->delete();
    }
}

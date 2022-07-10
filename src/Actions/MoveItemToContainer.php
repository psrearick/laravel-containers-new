<?php

namespace Psrearick\Containers\Actions;

use Psrearick\Containers\Containers;
use Psrearick\Containers\Contracts\ContainerContract;
use Psrearick\Containers\Contracts\ItemContract;
use Psrearick\Containers\Services\ContainerItemParameters;

class MoveItemToContainer
{
    public function execute(
        ItemContract $item,
        ContainerContract $source,
        ContainerContract $destination,
        ContainerItemParameters $parameters
    ) : void {
        $currentParameters  = app(Containers::class)->getParameters($item, $source);
        $containerItem      = app(Containers::class)->getContainerItem($item, $source);
        $currentQuantity    = $currentParameters->get('quantity');
        $moveQuantity       = $parameters->get('quantity');

        if (! $containerItem) {
            return;
        }

        if ($currentQuantity === null) {
            return;
        }

        if ($currentQuantity < abs($moveQuantity)) {
            return;
        }

        app(Containers::class)->removeItemFromContainer($item, $source, $parameters);

        $parameters->set('quantity', abs($moveQuantity));
        app(Containers::class)->addItemToContainer($item, $destination, $parameters);
    }
}

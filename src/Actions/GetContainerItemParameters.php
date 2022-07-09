<?php

namespace Psrearick\Containers\Actions;

use Psrearick\Containers\Containers;
use Psrearick\Containers\Contracts\ContainerContract;
use Psrearick\Containers\Contracts\ItemContract;
use Psrearick\Containers\Services\ContainerItemParameters;

class GetContainerItemParameters
{
    public function execute(ItemContract $item, ContainerContract $container) : ContainerItemParameters
    {
        $containerItem = app(Containers::class)->getContainerItem($item, $container);

        $parameters = app(ContainerItemParameters::class);

        if ($containerItem) {
            $parameters->setAll($containerItem->parameters);
        }

        return $parameters;
    }
}

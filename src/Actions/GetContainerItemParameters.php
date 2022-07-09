<?php

namespace Psrearick\Containers\Actions;

use JsonException;
use Psrearick\Containers\Containers;
use Psrearick\Containers\Contracts\ContainerContract;
use Psrearick\Containers\Contracts\ItemContract;
use Psrearick\Containers\Services\ContainerItemParameters;

class GetContainerItemParameters
{
    /**
     * @throws JsonException
     */
    public function execute(ItemContract $item, ContainerContract $container) : ContainerItemParameters
    {
        $containerItem = app(Containers::class)->getContainerItem($item, $container);

        $parameters = app(ContainerItemParameters::class);

        if ($containerItem) {
            $parameters->setAll(json_decode($containerItem->parameters, true, 512, JSON_THROW_ON_ERROR));
        }

        return $parameters;
    }
}

<?php

namespace Psrearick\Containers\Actions;

use JsonException;
use Psrearick\Containers\Containers;
use Psrearick\Containers\Contracts\ContainerContract;
use Psrearick\Containers\Contracts\ItemContract;
use Psrearick\Containers\Services\ContainerItemParameters;
use Psrearick\Containers\Services\ContainerItemRequest;

class RemoveItemFromContainer
{
    /**
     * @throws JsonException
     */
    public function execute(ItemContract $item, ContainerContract $container, ContainerItemParameters $parameters) : void
    {
        $currentParameters  = app(Containers::class)->getParameters($item, $container);
        $containerItem      = app(Containers::class)->getContainerItem($item, $container);
        $currentQuantity    = $currentParameters->get('quantity');

        if (! $containerItem) {
            return;
        }

        if ($currentQuantity === null) {
            return;
        }

        $newQuantity = $currentQuantity - abs($parameters->get('quantity'));

        $request = new ContainerItemRequest(
            'remove',
            $container,
            $item,
            $parameters,
            null,
            $currentParameters
        );

        $parameters = app(InvokeUpdateParameterActions::class)->execute($item, $request) ?? $parameters;
        $parameters = app(InvokeUpdateParameterActions::class)->execute($container, $request) ?? $parameters;

        if ($newQuantity <= 0) {
            $containerItem->delete();
        } else {
            $parameters->set('quantity', $newQuantity);
            $containerItem->parameters = json_encode($parameters->getAll(), JSON_THROW_ON_ERROR);
            $containerItem->save();
        }
    }
}

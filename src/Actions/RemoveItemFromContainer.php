<?php

namespace Psrearick\Containers\Actions;

use Psrearick\Containers\Containers;
use Psrearick\Containers\Contracts\ContainerContract;
use Psrearick\Containers\Contracts\ItemContract;
use Psrearick\Containers\Services\ContainerItemParameters;
use Psrearick\Containers\Services\ContainerItemRequest;

class RemoveItemFromContainer
{
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
        $request->setParameters($parameters);

        $parameters =
            (app(InvokeUpdateParameterActions::class)->execute($container, $request) ?? $parameters)
            ->set('quantity', $newQuantity);

        if ($newQuantity <= 0) {
            $containerItem->delete();

            return;
        }

        $containerItem->parameters = $parameters->getAll();
        $containerItem->save();
    }
}

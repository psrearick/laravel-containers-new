<?php

namespace Psrearick\Containers\Tests\CardManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Psrearick\Containers\Contracts\ContainerContract as Container;
use Psrearick\Containers\Services\ContainerItemParameters;
use Psrearick\Containers\Services\ContainerItemRequest;
use Psrearick\Containers\Tests\CardManagement\Factories\CollectionFactory;
use Psrearick\Containers\Tests\CardManagement\Models\Traits\HasCollectionCardActions;

class Collection extends Model implements Container
{
    use HasCollectionCardActions;
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory() : CollectionFactory
    {
        return CollectionFactory::new();
    }

    private function addCardToCollection(ContainerItemRequest $request) : ContainerItemParameters
    {
        $currentParameters  = $request->getCurrentParameters();
        $parameters         = $request->getParameters();
        $actualPrice        = $request->getItem()->price;
        $currentValue       = $this->value;
        $currentValueAdded  = $this->value_when_added;
        $addedQuantity      = $parameters->get('quantity');

        if (! $currentParameters->has('quantity')) {
            $this->update([
                'value'             => $currentValue + ($actualPrice * $addedQuantity),
                'value_when_added'  => $currentValueAdded + ($actualPrice * $addedQuantity),
            ]);

            return $parameters;
        }

        $currentPrice       = $currentParameters->get('price') ?? $actualPrice;
        $currentQuantity    = $currentParameters->get('quantity');
        $clearedValue       = $currentValue - ($currentPrice * $currentQuantity);
        $clearedValueAdded  = $currentValueAdded - ($currentParameters->get('price_when_added') ?? $currentPrice);
        $newQuantity        = ($addedQuantity + $currentQuantity);

        $this->update([
            'value'            => $clearedValue + ($actualPrice * $newQuantity),
            'value_when_added' => $clearedValueAdded + ($parameters->get('price_when_added') * $newQuantity),
        ]);

        return $parameters;
    }

    private function removeCardFromCollection(ContainerItemRequest $request) : ContainerItemParameters
    {
        $parameters = $request->getParameters();
        $price      = $request->getItem()->price;
        $quantity   = abs($parameters->get('quantity'));
        $change     = $price * $quantity;

        $this->update(['value' => $this->value - $change]);

        return $parameters;
    }

    private function updateParameterInCollection(ContainerItemRequest $request) : ContainerItemParameters
    {
        $currentParameters  = $request->getCurrentParameters();
        $parameters         = $request->getParameters();
        $actualPrice        = $request->getItem()->price;
        $currentValue       = $this->value;
        $addedQuantity      = $parameters->get('quantity');
        $value              = $currentValue + ($actualPrice * $addedQuantity);

        if ($currentParameters->has('quantity')) {
            $currentQuantity    = $currentParameters->get('quantity');
            $value              = $currentValue
                - (($currentParameters->get('price') ?? $actualPrice) * $currentQuantity)
                + ($actualPrice * ($addedQuantity + $currentQuantity));
        }

        $this->update(['value' => $value]);

        return $parameters;
    }
}

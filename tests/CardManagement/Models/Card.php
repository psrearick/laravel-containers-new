<?php

namespace Psrearick\Containers\Tests\CardManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Psrearick\Containers\Contracts\ItemContract as Item;
use Psrearick\Containers\Services\ContainerItemParameters;
use Psrearick\Containers\Services\ContainerItemRequest;
use Psrearick\Containers\Tests\CardManagement\Factories\CardFactory;

class Card extends Model implements Item
{
    use HasFactory;

    protected $guarded = [];

    public function updateParametersAction(ContainerItemRequest $request) : ContainerItemParameters
    {
        if ($request->getAction() === 'add') {
            return $this->addCardToCollection($request);
        }

        if ($request->getAction() === 'remove') {
            return $this->removeCardFromCollection($request);
        }

        if ($request->getAction() === 'move') {
            return $this->moveCardToCollection($request);
        }

        if ($request->getAction() === 'update') {
            return $this->updateParameterInCollection($request);
        }

        return $request->getParameters();
    }

    protected static function newFactory() : CardFactory
    {
        return CardFactory::new();
    }

    private function addCardToCollection(ContainerItemRequest $request) : ContainerItemParameters
    {
        $currentParameters  = $request->getCurrentParameters();
        $parameters         = $request->getParameters();

        if ($currentParameters->has('quantity')) {
            $currentPrice    = $currentParameters->get('price');
            $currentQuantity = $currentParameters->get('quantity');

            $parameters->set('price', $this->price);

            if (! $parameters->get('quantity')) {
                return $parameters;
            }

            $priceWhenAdded = (($currentPrice * $currentQuantity)
                + ($this->price * $parameters->get('quantity')))
                / ($currentQuantity + $parameters->get('quantity'));

            $parameters->set('price_when_added', $priceWhenAdded);

            return $parameters;
        }

        $parameters->set('price', $this->price);
        $parameters->set('price_when_added', $this->price);

        return $parameters;
    }

    private function moveCardToCollection(ContainerItemRequest $request) : ContainerItemParameters
    {
        return $request->getParameters();
    }

    private function removeCardFromCollection(ContainerItemRequest $request) : ContainerItemParameters
    {
        return $request->getParameters();
    }

    private function updateParameterInCollection(ContainerItemRequest $request) : ContainerItemParameters
    {
        $currentParameters  = $request->getCurrentParameters();
        $parameters         = $request->getParameters();

        $parameters->set('price', $this->price);

        if ($currentParameters->has('quantity')) {
            return $parameters;
        }

        $parameters->set('price_when_added', $this->price);

        return $parameters;
    }
}

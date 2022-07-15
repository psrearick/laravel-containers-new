<?php

namespace Psrearick\Containers\Tests\CardManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Psrearick\Containers\Containers;
use Psrearick\Containers\Contracts\ContainerContract;
use Psrearick\Containers\Contracts\ItemContract;
use Psrearick\Containers\Services\ContainerItemRequest;
use Psrearick\Containers\Tests\CardManagement\Factories\FolderFactory;
use Psrearick\Containers\Tests\CardManagement\Models\Traits\HasCollectionCardActions;
use Psrearick\Containers\Tests\CardManagement\Models\Traits\HasPropagatingActions;

class Folder extends Model implements ContainerContract, ItemContract
{
    use HasCollectionCardActions;
    use HasFactory;
    use HasPropagatingActions;

    protected $guarded = [];

    public function addContainerItem(ContainerItemRequest $request) : void
    {
        $item               = $request->getItem();
        $container          = $request->getContainer();
        $parameters         = $request->getParameters();
        $currentParameters  = $request->getCurrentParameters();
        $containerItem      = null;

        if ($item instanceof Collection) {
            $this->update([
                'value'            => $this->value + $item->value,
                'value_when_added' => $this->value_when_added + $item->value_when_added,
            ]);

            return;
        }

        if ($container instanceof Collection) {
            $containerItem = app(Containers::class)->getContainerItem($container, $this);
        }

        if ($containerItem && $currentParameters->has('quantity')) {
            $actualPrice        = $item->price;
            $currentValue       = $this->value;
            $currentValueAdded  = $this->value_when_added;
            $newQuantity        = $parameters->get('quantity');
            $currentPrice       = $currentParameters->get('price') ?? $actualPrice;
            $currentQuantity    = $currentParameters->get('quantity');
            $clearedValue       = $currentValue - ($currentPrice * $currentQuantity);
            $clearedValueAdded  = $currentValueAdded -
                ($currentParameters->get('price_when_added') ?? $currentPrice) * $currentQuantity;

            $this->update([
                'value'            => $clearedValue + ($actualPrice * $newQuantity),
                'value_when_added' => $clearedValueAdded + ($parameters->get('price_when_added') * $newQuantity),
            ]);

            return;
        }

        if ($item instanceof Card) {
            $this->update([
                'value'            => $this->value +
                    $parameters->get('quantity') * $parameters->get('price'),
                'value_when_added' => $this->value_when_added +
                    $parameters->get('quantity') * $parameters->get('price_when_added'),
            ]);
        }
    }

    public function removeContainerItem(ContainerItemRequest $request) : void
    {
        // TODO: Implement remove() method.
    }

    public function updateContainerItem(ContainerItemRequest $request) : void
    {
        // TODO: Implement updateContainerItem() method.
    }

    protected static function newFactory() : FolderFactory
    {
        return FolderFactory::new();
    }
}

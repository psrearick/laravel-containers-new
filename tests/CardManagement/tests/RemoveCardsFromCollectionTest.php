<?php

use Psrearick\Containers\Containers;
use Psrearick\Containers\Services\ContainerItemParameters;
use Psrearick\Containers\Tests\CardManagement\Models\Card;
use Psrearick\Containers\Tests\CardManagement\Models\Collection;

test('a card can be removed from a collection', function () {
    $card       = Card::factory()->create();
    $collection = Collection::factory()->create();
    $parameters = new ContainerItemParameters();

    $parameters->set('quantity', 1);

    app(Containers::class)->addItemToContainer($card, $collection, $parameters);

    app(Containers::class)->removeItemFromContainer($card, $collection, 1);

    $setParameters = app(Containers::class)->getParameters($card, $collection);

    $this->assertNull($setParameters->get('quantity'));
});

<?php

use Psrearick\Containers\Containers;
use Psrearick\Containers\Services\ContainerItemParameters;
use Psrearick\Containers\Tests\CardManagement\Models\Card;
use Psrearick\Containers\Tests\CardManagement\Models\Collection;

test('a collection can retrieve its cards', function () {
    $card       = Card::factory()->create();
    $collection = Collection::factory()->create();
    $parameters = new ContainerItemParameters();

    $parameters->set('quantity', 3);

    app(Containers::class)->addItemToContainer($card, $collection, $parameters);

    $cards = app(Containers::class)->getContainerItems($collection, Card::class);

    $this->assertCount(1, $cards);
    $this->assertEquals(3, $cards[0]['parameters']['quantity']);
});

test('a collection can have multiple cards', function () {
    $card       = Card::factory()->create();
    $collection = Collection::factory()->create();
    $parameters = new ContainerItemParameters();

    $parameters->set('quantity', 3);

    app(Containers::class)->addItemToContainer($card, $collection, $parameters);

    $card2 = Card::factory()->create();
    $parameters->clear();
    $parameters->set('quantity', 2);

    app(Containers::class)->addItemToContainer($card2, $collection, $parameters);

    $cards = app(Containers::class)->getContainerItems($collection, Card::class);

    $this->assertCount(2, $cards);
    $this->assertEquals(3, $cards[0]['parameters']['quantity']);
    $this->assertEquals($card->price, $cards[0]['parameters']['price']);
    $this->assertEquals(2, $cards[1]['parameters']['quantity']);
    $this->assertEquals($card2->price, $cards[1]['parameters']['price']);
    $this->assertEquals(($card->price * 3) + ($card2->price * 2), $collection->value);

    $parameters->clear();
    $parameters->set('quantity', -1);

    app(Containers::class)->removeItemFromContainer($card, $collection, $parameters);

    $this->assertEquals(($card->price * 2) + ($card2->price * 2), $collection->value);
});

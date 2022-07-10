<?php

use Psrearick\Containers\Containers;
use Psrearick\Containers\Services\ContainerItemParameters;
use Psrearick\Containers\Tests\CardManagement\Models\Card;
use Psrearick\Containers\Tests\CardManagement\Models\Collection;

test('A card can move from one collection to another', function () {
    $card       = Card::factory()->create();
    $collection = Collection::factory()->create();
    $parameters = new ContainerItemParameters();

    $parameters->set('quantity', 1);

    app(Containers::class)->addItemToContainer($card, $collection, $parameters);

    $collection2 = Collection::factory()->create();

    app(Containers::class)->moveItemToContainer($card, $collection, $collection2, $parameters);

    $collectionItem   = app(Containers::class)->getContainerItem($card, $collection);
    $collectionItems  = app(Containers::class)->getContainerItems($collection, Card::class);
    $collection2Items = app(Containers::class)->getContainerItems($collection2, Card::class);

    $this->assertNull($collectionItem);
    $this->assertCount(0, $collectionItems);
    $this->assertCount(1, $collection2Items);
});

test('A card can move to a populated container', function () {
    $card       = Card::factory()->create();
    $collection = Collection::factory()->create();
    $parameters = new ContainerItemParameters();

    $parameters->set('quantity', 1);

    app(Containers::class)->addItemToContainer($card, $collection, $parameters);

    $card2          = Card::factory()->create();
    $collection2    = Collection::factory()->create();

    app(Containers::class)->addItemToContainer($card2, $collection2, $parameters);

    app(Containers::class)->moveItemToContainer($card, $collection, $collection2, $parameters);

    $collectionItem   = app(Containers::class)->getContainerItem($card, $collection);
    $collectionItems  = app(Containers::class)->getContainerItems($collection, Card::class);
    $collection2Items = app(Containers::class)->getContainerItems($collection2, Card::class);

    $this->assertNull($collectionItem);
    $this->assertCount(0, $collectionItems);
    $this->assertCount(2, $collection2Items);
});

test('A card can move from a populated container', function () {
    $card       = Card::factory()->create();
    $card2      = Card::factory()->create();
    $collection = Collection::factory()->create();
    $parameters = new ContainerItemParameters();

    $parameters->set('quantity', 1);

    app(Containers::class)->addItemToContainer($card, $collection, $parameters);
    app(Containers::class)->addItemToContainer($card2, $collection, $parameters);

    $collection2 = Collection::factory()->create();

    app(Containers::class)->moveItemToContainer($card, $collection, $collection2, $parameters);

    $collectionItem   = app(Containers::class)->getContainerItem($card, $collection);
    $collectionItems  = app(Containers::class)->getContainerItems($collection, Card::class);
    $collection2Items = app(Containers::class)->getContainerItems($collection2, Card::class);

    $this->assertNull($collectionItem);
    $this->assertCount(1, $collectionItems);
    $this->assertCount(1, $collection2Items);
});

test('A card can move from a populated container to a populated container', function () {
    $card       = Card::factory()->create();
    $card1      = Card::factory()->create();
    $collection = Collection::factory()->create();
    $parameters = new ContainerItemParameters();

    $parameters->set('quantity', 1);

    app(Containers::class)->addItemToContainer($card, $collection, $parameters);
    app(Containers::class)->addItemToContainer($card1, $collection, $parameters);

    $card2          = Card::factory()->create();
    $collection2    = Collection::factory()->create();

    app(Containers::class)->addItemToContainer($card2, $collection2, $parameters);

    app(Containers::class)->moveItemToContainer($card, $collection, $collection2, $parameters);

    $collectionItem   = app(Containers::class)->getContainerItem($card, $collection);
    $collectionItems  = app(Containers::class)->getContainerItems($collection, Card::class);
    $collection2Items = app(Containers::class)->getContainerItems($collection2, Card::class);

    $this->assertNull($collectionItem);
    $this->assertCount(1, $collectionItems);
    $this->assertCount(2, $collection2Items);
});

test('A card can move some of its quantity from one collection to another', function () {
    $card       = Card::factory()->create();
    $collection = Collection::factory()->create();
    $parameters = new ContainerItemParameters();

    $parameters->set('quantity', 3);

    app(Containers::class)->addItemToContainer($card, $collection, $parameters);

    $collection2 = Collection::factory()->create();

    $parameters->clear();
    $parameters->set('quantity', 2);

    app(Containers::class)->moveItemToContainer($card, $collection, $collection2, $parameters);

    $collectionItem   = app(Containers::class)->getContainerItem($card, $collection);
    $collectionItem2  = app(Containers::class)->getContainerItem($card, $collection2);
    $collectionItems  = app(Containers::class)->getContainerItems($collection, Card::class);
    $collection2Items = app(Containers::class)->getContainerItems($collection2, Card::class);

    $this->assertEquals(1, $collectionItem?->parameters['quantity']);
    $this->assertEquals(2, $collectionItem2?->parameters['quantity']);
    $this->assertCount(1, $collectionItems);
    $this->assertCount(1, $collection2Items);
});

test('Moving a card to a container can merge quantities', function () {
    $card        = Card::factory()->create();
    $collection  = Collection::factory()->create();
    $collection2 = Collection::factory()->create();
    $parameters  = new ContainerItemParameters();

    $parameters->set('quantity', 2);
    app(Containers::class)->addItemToContainer($card, $collection2, $parameters);

    $parameters->clear();
    $parameters->set('quantity', 1);
    app(Containers::class)->addItemToContainer($card, $collection, $parameters);
    app(Containers::class)->moveItemToContainer($card, $collection, $collection2, $parameters);

    $collectionItem   = app(Containers::class)->getContainerItem($card, $collection);
    $collectionItem2  = app(Containers::class)->getContainerItem($card, $collection2);
    $collectionItems  = app(Containers::class)->getContainerItems($collection, Card::class);
    $collection2Items = app(Containers::class)->getContainerItems($collection2, Card::class);

    $this->assertNull($collectionItem);
    $this->assertCount(0, $collectionItems);
    $this->assertCount(1, $collection2Items);
    $this->assertEquals(3, $collectionItem2?->parameters['quantity']);
});

test('Moving a card to a container accurately updates both containers value', function () {
    $card        = Card::factory()->create();
    $collection  = Collection::factory()->create();
    $collection2 = Collection::factory()->create();
    $parameters  = new ContainerItemParameters();

    $parameters->set('quantity', 2);

    app(Containers::class)->addItemToContainer($card, $collection, $parameters);
    app(Containers::class)->moveItemToContainer($card, $collection, $collection2, $parameters);

    $collectionItem   = app(Containers::class)->getContainerItem($card, $collection);
    $collectionItem2  = app(Containers::class)->getContainerItem($card, $collection2);
    $collectionItems  = app(Containers::class)->getContainerItems($collection, Card::class);
    $collection2Items = app(Containers::class)->getContainerItems($collection2, Card::class);

    $this->assertNull($collectionItem);
    $this->assertCount(0, $collectionItems);
    $this->assertCount(1, $collection2Items);
    $this->assertEquals($card->price, $collectionItem2?->parameters['price']);
    $this->assertEquals($card->price, $collectionItem2?->parameters['price_when_added']);
    $this->assertEquals($card->price * 2, $collection2?->value);
    $this->assertEquals($card->price * 2, $collection2?->value_when_added);
    $this->assertEquals(0, $collection?->value);
    $this->assertEquals(0, $collection?->value_when_added);
});

test('Moving a card to a populated container accurately updates both containers value', function () {
    $card        = Card::factory()->create();
    $card2       = Card::factory()->create();
    $collection  = Collection::factory()->create();
    $collection2 = Collection::factory()->create();
    $parameters  = new ContainerItemParameters();

    $parameters->set('quantity', 2);
    app(Containers::class)->addItemToContainer($card2, $collection2, $parameters);
    app(Containers::class)->addItemToContainer($card, $collection, $parameters);
    app(Containers::class)->moveItemToContainer($card, $collection, $collection2, $parameters);

    $collectionItem   = app(Containers::class)->getContainerItem($card, $collection);
    $collectionItems  = app(Containers::class)->getContainerItems($collection, Card::class);
    $collection2Items = app(Containers::class)->getContainerItems($collection2, Card::class);

    $this->assertNull($collectionItem);
    $this->assertCount(0, $collectionItems);
    $this->assertCount(2, $collection2Items);
    $this->assertEquals(($card->price * 2 + $card2->price * 2), $collection2?->value);
    $this->assertEquals(($card->price * 2 + $card2->price * 2), $collection2?->value_when_added);
    $this->assertEquals(0, $collection?->value);
    $this->assertEquals(0, $collection?->value_when_added);
});

test(
    'Moving a card from a populated container to a populated container accurately updates both containers value',
    function () {
        $card        = Card::factory()->create();
        $card1       = Card::factory()->create();
        $card2       = Card::factory()->create();
        $collection  = Collection::factory()->create();
        $collection2 = Collection::factory()->create();
        $parameters  = new ContainerItemParameters();

        $parameters->set('quantity', 2);
        app(Containers::class)->addItemToContainer($card1, $collection, $parameters);

        $parameters->clear();
        $parameters->set('quantity', 3);
        app(Containers::class)->addItemToContainer($card, $collection, $parameters);

        $parameters->clear();
        $parameters->set('quantity', 4);
        app(Containers::class)->addItemToContainer($card2, $collection2, $parameters);

        $parameters->clear();
        $parameters->set('quantity', 3);
        app(Containers::class)->moveItemToContainer($card, $collection, $collection2, $parameters);

        $collectionItems  = app(Containers::class)->getContainerItems($collection, Card::class);
        $collection2Items = app(Containers::class)->getContainerItems($collection2, Card::class);

        $this->assertCount(1, $collectionItems);
        $this->assertCount(2, $collection2Items);
        $this->assertEquals(($card->price * 3 + $card2->price * 4), $collection2?->value);
        $this->assertEquals(($card->price * 3 + $card2->price * 4), $collection2?->value_when_added);
        $this->assertEquals($card1->price * 2, $collection?->value);
        $this->assertEquals($card1->price * 2, $collection?->value_when_added);
    }
);

test('Partially moving a card to a container accurately updates both containers value', function () {
    $card        = Card::factory()->create();
    $collection  = Collection::factory()->create();
    $collection2 = Collection::factory()->create();
    $parameters  = new ContainerItemParameters();

    $parameters->set('quantity', 3);
    $parameters->set('price', $card->price);

    app(Containers::class)->addItemToContainer($card, $collection, $parameters);

    $parameters->clear();
    $parameters->set('quantity', 2);
    app(Containers::class)->moveItemToContainer($card, $collection, $collection2, $parameters);

    $collectionItem2  = app(Containers::class)->getContainerItem($card, $collection2);
    $collectionItems  = app(Containers::class)->getContainerItems($collection, Card::class);
    $collection2Items = app(Containers::class)->getContainerItems($collection2, Card::class);

    $this->assertCount(1, $collectionItems);
    $this->assertCount(1, $collection2Items);
    $this->assertEquals($card->price, $collectionItem2?->parameters['price']);
    $this->assertEquals($card->price, $collectionItem2?->parameters['price_when_added']);
    $this->assertEquals($card->price * 2, $collection2?->value);
    $this->assertEquals($card->price * 2, $collection2?->value_when_added);
    $this->assertEquals($card->price, $collection?->value);
    $this->assertEquals($card->price, $collection?->value_when_added);
});

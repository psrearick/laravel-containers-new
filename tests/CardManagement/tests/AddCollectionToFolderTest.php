<?php

use Psrearick\Containers\Containers;
use Psrearick\Containers\Services\ContainerItemParameters;
use Psrearick\Containers\Tests\CardManagement\Models\Card;
use Psrearick\Containers\Tests\CardManagement\Models\Collection;
use Psrearick\Containers\Tests\CardManagement\Models\Folder;

test('a collection can be added to a folder', function () {
    $folder     = Folder::factory()->create();
    $collection = Collection::factory()->create();

    app(Containers::class)->addSingletonItemToContainer($collection, $folder);

    $setParameters = app(Containers::class)->getParameters($collection, $folder);

    $this->assertEquals(1, $setParameters->get('quantity'));
});

test('a collection can only be added to a folder once', function () {
    $folder     = Folder::factory()->create();
    $collection = Collection::factory()->create();

    app(Containers::class)->addItemToContainer($collection, $folder);
    app(Containers::class)->addItemToContainer($collection, $folder);

    $setParameters = app(Containers::class)->getParameters($collection, $folder);

    $this->assertEquals(1, $setParameters->get('quantity'));
});

test('a collection can be removed from a folder', function () {
    $folder     = Folder::factory()->create();
    $collection = Collection::factory()->create();

    app(Containers::class)->addItemToContainer($collection, $folder);
    app(Containers::class)->removeItemFromContainer($collection, $folder);

    $setParameters = app(Containers::class)->getParameters($collection, $folder);

    $this->assertEquals(0, $setParameters->get('quantity'));
});

test('a collection can only be removed from a folder once', function () {
    $folder     = Folder::factory()->create();
    $collection = Collection::factory()->create();

    app(Containers::class)->addItemToContainer($collection, $folder);
    app(Containers::class)->removeItemFromContainer($collection, $folder);
    app(Containers::class)->removeItemFromContainer($collection, $folder);

    $setParameters = app(Containers::class)->getParameters($collection, $folder);

    $this->assertEquals(0, $setParameters->get('quantity'));
});

test('a collection can be removed from a folder and added back', function () {
    $folder     = Folder::factory()->create();
    $collection = Collection::factory()->create();

    app(Containers::class)->addItemToContainer($collection, $folder);
    app(Containers::class)->removeItemFromContainer($collection, $folder);
    app(Containers::class)->addItemToContainer($collection, $folder);

    $setParameters = app(Containers::class)->getParameters($collection, $folder);

    $this->assertEquals(1, $setParameters->get('quantity'));
});

test('a folder can update it totals when a collection is added', function () {
    $folder     = Folder::factory()->create();
    $collection = Collection::factory()->create();
    $card       = Card::factory()->create();

    $parameters = new ContainerItemParameters();
    $parameters->set('quantity', 3);

    app(Containers::class)->addItemToContainer($card, $collection, $parameters);

    app(Containers::class)->addSingletonItemToContainer($collection, $folder);

    $this->assertEquals(3 * $card->price, $folder->value);
    $this->assertEquals(3 * $card->price, $folder->value_when_added);
});

test('a folder can update it totals when a collections value changes', function () {
    $folder     = Folder::factory()->create();
    $collection = Collection::factory()->create();
    $card       = Card::factory()->create();
    $parameters = new ContainerItemParameters();

    $parameters->set('quantity', 3);

    app(Containers::class)->addItemToContainer($card, $collection, $parameters);

    app(Containers::class)->addSingletonItemToContainer($collection, $folder);

    $card2 = Card::factory()->create();

    $parameters->clear();
    $parameters->set('quantity', 2);
    app(Containers::class)->addItemToContainer($card2, $collection, $parameters);

    $folder->refresh();
    $this->assertEquals(3 * $card->price + 2 * $card2->price, $folder->value);
    $this->assertEquals(3 * $card->price + 2 * $card2->price, $folder->value_when_added);

    $firstPrice = $card->price;
    $newPrice   = $firstPrice * 0.75;
    $card->update(['price' => $newPrice]);

    $parameters->clear();
    $parameters->set('quantity', 5);
    $parameters->set('price', $newPrice);
    app(Containers::class)->addItemToContainer($card, $collection, $parameters);

    $folder->refresh();
    $this->assertEquals(8 * $card->price + 2 * $card2->price, $folder->value);
    $this->assertEquals(3 * $firstPrice + 5 * $newPrice + 2 * $card2->price, $folder->value_when_added);
});

<?php

use Psrearick\Containers\Containers;
use Psrearick\Containers\Services\ContainerItemParameters;
use Psrearick\Containers\Tests\CardManagement\Models\Card;
use Psrearick\Containers\Tests\CardManagement\Models\Collection;
use Psrearick\Containers\Tests\CardManagement\Models\Folder;

test('collections can be nested', function () {
    $folder     = Folder::factory()->create();
    $folder2    = Folder::factory()->create();

    app(Containers::class)->nestContainer($folder, $folder2);

    $this->assertCount(1, app(Containers::class)->getChildrenContainers($folder2, Folder::class));
    $this->assertNotNull(app(Containers::class)->getChildContainerItem($folder, $folder2));
});

test('nested collections can be un-nested', function () {
    $folder     = Folder::factory()->create();
    $folder2    = Folder::factory()->create();

    app(Containers::class)->nestContainer($folder, $folder2);
    app(Containers::class)->unnestContainer($folder);

    $this->assertCount(0, app(Containers::class)->getChildrenContainers($folder2, Folder::class));
    $this->assertNull(app(Containers::class)->getChildContainerItem($folder, $folder2));
});

test('collections can be moved', function () {
    $collection     = Folder::factory()->create();
    $collection2    = Folder::factory()->create();
    $collection3    = Folder::factory()->create();

    app(Containers::class)->nestContainer($collection, $collection2);
    app(Containers::class)->moveContainer($collection, $collection3);

    $this->assertCount(0, app(Containers::class)->getChildrenContainers($collection2, Folder::class));
    $this->assertNull(app(Containers::class)->getChildContainerItem($collection, $collection2));
    $this->assertCount(1, app(Containers::class)->getChildrenContainers($collection3, Folder::class));
    $this->assertNotNull(app(Containers::class)->getChildContainerItem($collection, $collection3));
});

//test('nesting a populated collection updates its parents totals', function () {
//    $collection     = Collection::factory()->create();
//    $collection2    = Collection::factory()->create();
//    $card           = Card::factory()->create();
//    $parameters     = new ContainerItemParameters();
//
//    $parameters->set('quantity', 3);
//
//    app(Containers::class)->addItemToContainer($card, $collection, $parameters);
//
//    app(Containers::class)->nestContainer($collection, $collection2);
//
//    $this->assertEquals($card->price * 3, $collection?->value);
//    $this->assertEquals($card->price * 3, $collection?->value_when_added);
//    $this->assertEquals($card->price * 3, $collection2?->value);
//    $this->assertEquals($card->price * 3, $collection2?->value_when_added);
//});

<?php

use Psrearick\Containers\Containers;
use Psrearick\Containers\Tests\CardManagement\Models\Collection;

test('collections can be nested', function () {
    $collection     = Collection::factory()->create();
    $collection2    = Collection::factory()->create();

    app(Containers::class)->nestContainer($collection, $collection2);

    $this->assertCount(1, app(Containers::class)->getChildrenContainers($collection2, Collection::class));
    $this->assertNotNull(app(Containers::class)->getChildContainerItem($collection, $collection2));
});

test('nested collections can be un-nested', function () {
    $collection     = Collection::factory()->create();
    $collection2    = Collection::factory()->create();

    app(Containers::class)->nestContainer($collection, $collection2);
    app(Containers::class)->unnestContainer($collection);

    $this->assertCount(0, app(Containers::class)->getChildrenContainers($collection2, Collection::class));
    $this->assertNull(app(Containers::class)->getChildContainerItem($collection, $collection2));
});

test('collections can be moved', function () {
    $collection     = Collection::factory()->create();
    $collection2    = Collection::factory()->create();
    $collection3    = Collection::factory()->create();

    app(Containers::class)->nestContainer($collection, $collection2);
    app(Containers::class)->moveContainer($collection, $collection3);

    $this->assertCount(0, app(Containers::class)->getChildrenContainers($collection2, Collection::class));
    $this->assertNull(app(Containers::class)->getChildContainerItem($collection, $collection2));
    $this->assertCount(1, app(Containers::class)->getChildrenContainers($collection3, Collection::class));
    $this->assertNotNull(app(Containers::class)->getChildContainerItem($collection, $collection3));
});

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

//test('a collection can have multiple cards',
//    /**
//     * @throws JsonException
//     */
//    function () {
//        $card       = Card::factory()->create();
//        $collection = Collection::factory()->create();
//        $parameters = new ContainerItemParameters();
//
//        $parameters->set('quantity', 3);
//
//        app(Containers::class)->addItemToContainer($card, $collection, $parameters);
//
//        $parameters->set('quantity', 2);
//
//        app(Containers::class)->addItemToContainer($card, $collection, $parameters);
//
//        $setParameters = app(Containers::class)->getParameters($card, $collection);
//
//        $this->assertEquals(3, $setParameters->get('quantity'));
//    }
//);

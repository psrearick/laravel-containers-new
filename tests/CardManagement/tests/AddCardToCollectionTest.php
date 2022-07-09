<?php

use Psrearick\Containers\Containers;
use Psrearick\Containers\Services\ContainerItemParameters;
use Psrearick\Containers\Tests\CardManagement\Models\Card;
use Psrearick\Containers\Tests\CardManagement\Models\Collection;

test(
    'a card can be added to a collection',
    /**
     * @throws JsonException
     */
    function () {
        $card       = Card::factory()->create();
        $collection = Collection::factory()->create();
        $parameters = new ContainerItemParameters();

        $parameters->set('quantity', 1);

        app(Containers::class)->addItemToContainer($card, $collection, $parameters);

        $setParameters = app(Containers::class)->getParameters($card, $collection);

        $this->assertEquals(1, $setParameters->get('quantity'));
    }
);

test(
    'a card can increase its quantity in a collection',
    /**
     * @throws JsonException
     */
    function () {
        $card       = Card::factory()->create();
        $collection = Collection::factory()->create();
        $parameters = new ContainerItemParameters();

        $parameters->set('quantity', 1);

        app(Containers::class)->addItemToContainer($card, $collection, $parameters);

        $parameters->set('quantity', 2);

        app(Containers::class)->addItemToContainer($card, $collection, $parameters);

        $setParameters = app(Containers::class)->getParameters($card, $collection);

        $this->assertEquals(3, $setParameters->get('quantity'));
    }
);

test(
    'an item can track its price when being added',
    /**
     * @throws JsonException
     */
    function () {
        $card       = Card::factory()->create();
        $collection = Collection::factory()->create();
        $parameters = new ContainerItemParameters();
        $cardPrice1 = $card->price;

        $parameters->set('quantity', 1);
        $parameters->set('price', $cardPrice1);

        app(Containers::class)->addItemToContainer($card, $collection, $parameters);

        $setParameters = app(Containers::class)->getParameters($card, $collection);

        $this->assertEquals($cardPrice1, $setParameters->get('price'));
        $this->assertEquals($cardPrice1, $setParameters->get('price_when_added'));

        $cardPrice2 = $card->price * 2;

        $card->update(['price' => $cardPrice2]);

        $parameters->set('price', $cardPrice2);
        $parameters->set('quantity', 2);

        app(Containers::class)->addItemToContainer($card, $collection, $parameters);

        $setParameters = app(Containers::class)->getParameters($card, $collection);

        $this->assertEquals($cardPrice2, $setParameters->get('price'));

        $priceWhenAdded = ($cardPrice1 + ($cardPrice2 * 2)) / 3;

        $this->assertEquals($priceWhenAdded, $setParameters->get('price_when_added'));
    }
);

test(
    'a card can update its price without changing other parameters',
    /**
     * @throws JsonException
     */
    function () {
        $card       = Card::factory()->create();
        $collection = Collection::factory()->create();
        $parameters = new ContainerItemParameters();
        $cardPrice1 = $card->price;

        $parameters->set('quantity', 1);
        $parameters->set('price', $cardPrice1);

        app(Containers::class)->addItemToContainer($card, $collection, $parameters);

        $setParameters = app(Containers::class)->getParameters($card, $collection);

        $this->assertEquals($cardPrice1, $setParameters->get('price'));
        $this->assertEquals($cardPrice1, $setParameters->get('price_when_added'));

        $cardPrice2 = $card->price * 2;

        $card->update(['price' => $cardPrice2]);

        $parameters->clear();

        $parameters->set('price', $cardPrice2);

        app(Containers::class)->updateItemInContainer($card, $collection, $parameters);

        $setParameters = app(Containers::class)->getParameters($card, $collection);

        $this->assertEquals($cardPrice2, $setParameters->get('price'));
        $this->assertEquals($cardPrice1, $setParameters->get('price_when_added'));
    }
);

test(
    'a collection can track its value when being added',
    /**
     * @throws JsonException
     */
    function () {
        $card       = Card::factory()->create();
        $collection = Collection::factory()->create();
        $parameters = new ContainerItemParameters();
        $cardPrice1 = $card->price;

        $parameters->set('quantity', 1);
        $parameters->set('price', $cardPrice1);

        app(Containers::class)->addItemToContainer($card, $collection, $parameters);

        $this->assertEquals($cardPrice1, $collection->value);
        $this->assertEquals($cardPrice1, $collection->value_when_added);

        $parameters->clear();

        $cardPrice2 = $cardPrice1 * 2;

        $card->update(['price' => $cardPrice2]);

        $parameters->set('price', $cardPrice2);
        $parameters->set('quantity', 2);

        app(Containers::class)->addItemToContainer($card, $collection, $parameters);

        $priceWhenAdded = ($cardPrice1 + ($cardPrice2 * 2));

        $this->assertEquals($cardPrice2 * 3, $collection->value);
        $this->assertEquals($priceWhenAdded, $collection->value_when_added);

        $parameters->clear();

        $cardPrice3 = $cardPrice2 * 2;
        $card->update(['price' => $cardPrice3]);

        $parameters->set('price', $cardPrice3);

        app(Containers::class)->updateItemInContainer($card, $collection, $parameters);

        $this->assertEquals($cardPrice3 * 3, $collection->value);
        $this->assertEquals($priceWhenAdded, $collection->value_when_added);
    }
);

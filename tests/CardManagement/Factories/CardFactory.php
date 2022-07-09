<?php

namespace Psrearick\Containers\Tests\CardManagement\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Psrearick\Containers\Tests\CardManagement\Models\Card;

class CardFactory extends Factory
{
    protected $model = Card::class;

    public function definition() : array
    {
        return [
            'name'  => $this->faker->words(3, true),
            'price' => $this->faker->randomNumber(4),
        ];
    }
}

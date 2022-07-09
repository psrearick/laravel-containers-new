<?php

namespace Psrearick\Containers\Tests\CardManagement\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Psrearick\Containers\Tests\CardManagement\Models\Collection;

class CollectionFactory extends Factory
{
    protected $model = Collection::class;

    public function definition() : array
    {
        return [
            'name'  => $this->faker->words(3, true),
        ];
    }
}

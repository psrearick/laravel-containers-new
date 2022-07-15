<?php

namespace Psrearick\Containers\Tests\CardManagement\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Psrearick\Containers\Tests\CardManagement\Models\Folder;

class FolderFactory extends Factory
{
    protected $model = Folder::class;

    public function definition() : array
    {
        return [
            'name'  => $this->faker->words(3, true),
        ];
    }
}

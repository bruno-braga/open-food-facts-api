<?php

namespace Modules\Products\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FilesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Products\Models\Files::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}


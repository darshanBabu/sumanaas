<?php

namespace Database\Factories;

use App\Models\Products;
use Faker\Generator as Faker;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Products::class;

    public function definition()
    {
        return [
            'Name' => ucFirst($this->faker->unique()->word),
            'Price' => $this->faker->randomFloat(2, 10, 100),
            'Description' => $this->faker->sentence,
        ];
    }
}

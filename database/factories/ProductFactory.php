<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'ItemName' => $this->faker->name(),
            'ItemPrice' => $this->faker->numberBetween(0,99),
            'ItemProperties' => $this->faker->randomDigit(),
        ];
    }
}

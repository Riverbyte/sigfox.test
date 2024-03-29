<?php

namespace Database\Factories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Device::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'device' => $this->faker->sentence(),
            'name' => $this->faker->sentence(),
            'description' => $this->faker->sentence(),
            'user' => $this->faker->randomElement(['1','2'])
        ];
    }
}

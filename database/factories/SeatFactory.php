<?php

namespace Database\Factories;

use App\Models\Seat;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seat>
 */
class SeatFactory extends Factory
{
    protected $model = Seat::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'venue_id' => Venue::factory(), // Değer dışarıdan sağlanacak
            'section' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'row' => $this->faker->randomElement(range(1, 20)),
            'number' => $this->faker->randomElement(range(1, 50)),
            'price' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}

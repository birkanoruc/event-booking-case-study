<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\Seat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReservationItem>
 */
class ReservationItemFactory extends Factory
{
    protected $model = ReservationItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reservation_id' => null,
            'seat_id' => null,
            'price' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}

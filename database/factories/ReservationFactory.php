<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'event_id' => null, // Değer dışarıdan sağlanacak
            'total_amount' => $this->faker->randomFloat(2, 50, 500),
            'expires_at' => Carbon::now()->addMinutes(15), // Rezervasyon süresi
        ];
    }
}

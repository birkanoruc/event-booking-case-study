<?php

namespace Database\Factories;

use App\Models\SeatBlock;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SeatBlock>
 */
class SeatBlockFactory extends Factory
{
    protected $model = SeatBlock::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'seat_id' => null, // Değer dışarıdan sağlanacak
            'user_id' => User::factory(),
            'event_id' => null, // Değer dışarıdan sağlanacak
            'expires_at' => Carbon::now()->addMinutes(15),
        ];
    }
}

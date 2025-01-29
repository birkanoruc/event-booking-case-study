<?php

namespace Database\Seeders;

use App\Models\Seat;
use App\Models\Venue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 200 adet normal kullanıcı oluştur
        Venue::factory(10)
            ->has(
                Seat::factory(50),
            )->create();
    }
}

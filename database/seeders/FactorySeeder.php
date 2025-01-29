<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\Seat;
use App\Models\SeatBlock;
use App\Models\Ticket;
use App\Models\Venue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FactorySeeder extends Seeder
{
    /**
     * Aralık verileri doğrultusunda rastgele üretilen sayılar adedinde sahte veri üretir.
     * Her bir mekan için:
     * - Koltuklar
     * - Etkinlikler
     * Her bir etkinlik için:
     * - Bloklu koltuklar
     * - Rezervasyonlar
     * Her bir rezervasyon için:
     * - Rezervasyon öğeleri
     * - Biletler
     */
    public function run(): void
    {
        $venueCount = fake()->numberBetween(50, 100);

        Venue::factory($venueCount)
            ->create()
            ->each(function ($venue) {
                // Koltukları oluştur
                $seatCount = fake()->numberBetween(100, 200);
                $seats = Seat::factory($seatCount)->create(['venue_id' => $venue->id])->pluck('id')->toArray();

                $eventCount = fake()->numberBetween(25, 50);
                Event::factory($eventCount)->create(['venue_id' => $venue->id])

                    ->each(function ($event) use (&$seats) {
                        // Etkinlik için seat block oluştur
                        $seatsBlockCount = fake()->numberBetween(10, 25);

                        // Her seat block için eşsiz seat_id kullanımı
                        $selectedSeats = array_splice($seats, 0, $seatsBlockCount); // Seatleri çek ve koleksiyondan çıkar

                        foreach ($selectedSeats as $seatId) {
                            SeatBlock::factory()->create([
                                'event_id' => $event->id,
                                'seat_id' => $seatId,
                            ]);
                        }

                        // Rezervasyonlar oluştur
                        $reservationCount = fake()->numberBetween(10, 25);
                        Reservation::factory($reservationCount)->create(['event_id' => $event->id])

                            ->each(function ($reservation) use (&$seats) {
                                // Rezervasyon öğeleri oluştur
                                $reservationItemCount = fake()->numberBetween(1, 5);
                                $selectedSeatsForReservation = array_splice($seats, 0, $reservationItemCount); // Rezervasyon için seat'ler

                                foreach ($selectedSeatsForReservation as $seatId) {
                                    ReservationItem::factory()->create([
                                        'reservation_id' => $reservation->id,
                                        'seat_id' => $seatId,
                                    ]);
                                }

                                // Biletler oluştur
                                $ticketCount = fake()->numberBetween(10, 25);
                                $selectedSeatsForTicket = array_splice($seats, 0, $ticketCount); // Bilet için seat'ler

                                foreach ($selectedSeatsForTicket as $seatId) {
                                    Ticket::factory()->create([
                                        'reservation_id' => $reservation->id,
                                        'seat_id' => $seatId,
                                    ]);
                                }
                            });
                    });
            });
    }
}

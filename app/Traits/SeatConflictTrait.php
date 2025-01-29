<?php

namespace App\Traits;

use App\Enums\SeatStatus;
use App\Exceptions\ConflictException;
use App\Exceptions\NotFoundException;
use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

trait SeatConflictTrait
{
    /**
     * Koltuk bloklama, rezervasyon veya biletleme çakışmalarını kontrol eder.
     *
     * @param  int $eventId
     * @param  int $seatId
     * @return array|null
     */
    public function checkSeatConflict(int $eventId, int $seatId): array|null
    {
        $event = Event::find($eventId); // Etkinlik aranır.

        if (!$event) {
            return ['event_id' => 'Etkinlik bulunamadı.']; // Etkinlik bulunmaz ise request'e hata eklenir.
        }

        $seat = $event->seats()->where("seats.id", $seatId)->first(); // Etkinliğe ait koltuk aranır.

        if (!$seat) {
            return ['seat_id' => 'Belirtilen koltuk etkinlikte bulunamadı.']; // Koltuk bulunmaz ise request'e hata eklenir.
        }

        // Koltuk durumu sorgulama
        $seatStatus = $seat->status;

        if ($seatStatus !== SeatStatus::AVAILABLE) {
            $message = $this->getSeatStatusMessage($seatStatus); // Detaylı mesaj al
            throw new ConflictException($message, 409); // Koltuk müsait değilse, duruma göre hata mesajı
        }

        return null; // Koltuk müsaitse null döndürür
    }

    // Koltuk durumu mesajlarını döndürme
    public function getSeatStatusMessage(SeatStatus $seatStatus): string
    {
        switch ($seatStatus) {
            case SeatStatus::SOLD:
                return "Koltuk zaten satılmış.";
            case SeatStatus::RESERVED:
                return "Koltuk rezervasyon sürecinde.";
            case SeatStatus::BLOCKED:
                return "Koltuk şu anda bloklanmış.";
            default:
                return "Koltuk mevcut.";
        }
    }
}

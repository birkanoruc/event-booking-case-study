<?php

namespace App\Repositories;

use App\Contracts\Repositories\ReservationItemRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Models\ReservationItem;

class ReservationItemRepository implements ReservationItemRepositoryInterface
{

    /**
     * Rezervasyon öğesini getirir
     * @param int $reservationItemId
     * @throws \App\Exceptions\NotFoundException
     * @return ReservationItem
     */
    public function getReservationItem(int $reservationItemId): ReservationItem
    {
        $reservationItem = ReservationItem::with("reservation")->find($reservationItemId);

        if (!$reservationItem) {
            throw new NotFoundException("Rezervasyon öğesi bulunamadı!", 404);
        }

        return $reservationItem;
    }

    /**
     * Rezervasyon öğesi ekler
     * @param array $reservationItemData
     * @return ReservationItem
     */
    public function createReservationItem(array $reservationItemData): ReservationItem
    {
        $reservationItem = ReservationItem::create($reservationItemData);
        return $reservationItem;
    }

    /**
     * Rezervasyon öğesini siler
     * @param int $reservationItemId
     * @return bool
     */
    public function deleteReservationItem(int $reservationItemId): bool
    {
        $reservationItem = $this->getReservationItem($reservationItemId);

        if ($reservationItem->delete()) {
            return true;
        }

        return false;
    }
}

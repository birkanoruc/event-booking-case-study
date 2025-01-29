<?php

namespace App\Contracts\Services;

use Illuminate\Http\JsonResponse;

interface TicketInterface
{
    /**
     * Tüm biletlerin listeleme sürecinin yönetileceği metodu tanımlar.
     * @return JsonResponse
     */
    public function getTickets(array $ticketFilterData): JsonResponse;

    /**
     * Belirli bir biletin bilgilerini getirme sürecinin yönetileceği metodu tanımlar.
     * @param int $ticketId
     * @return JsonResponse
     */
    public function getTicketById(int $ticketId): JsonResponse;

    /**
     * Belirli bir biletin indirilme sürecinin yönetileceği metodu tanımlar.
     * @param int $ticketId
     * @return JsonResponse
     */
    public function download(int $ticketId): JsonResponse;

    /**
     * Belirli bir biletin transfer sürecinin yönetileceği metodu tanımlar.
     * @param int $ticketId
     * @param string $email
     * @return JsonResponse
     */
    public function transfer(int $ticketId, string $email): JsonResponse;
}

<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Ticket;

/**
 * Interface TicketRepositoryInterface
 *
 * Bilet yönetimi işlemlerinin yapılacağı repo sınıfının uygulaması için gereklidir.
 * Bu interface, biletlerle ilgili veri erişim işlemlerini tanımlar.
 */
interface TicketRepositoryInterface
{
    /**
     * Oturum açan kullanıcıya ait tüm biletleri listeleyecek metodu tanımlar.
     * @param int $userId
     * @return Collection
     */
    public function getUserTickets(int $userId): Collection;

    /**
     * Oturum açan kullanıcıya ait belirli bir bileti getirecek metodu tanımlar.
     * @param int $userId
     * @param int $ticketId
     * @return Ticket
     */
    public function getUserTicketById(int $userId, int $ticketId): Ticket;

    /**
     * Yeni bir bilet oluşturacak metodu tanımlar.
     * @param array $ticketData
     * @return Ticket
     */
    public function createTicket(array $ticketData): Ticket;

    /**
     * Oturum açan kullanıcıya ait bileti transfer edecek metodu tanımlar.
     * @param int $userId
     * @param int $ticketId
     * @param int $email
     * @return bool
     */
    public function transferUserTicket(int $userId, int $ticketId, string $email): Ticket;
}

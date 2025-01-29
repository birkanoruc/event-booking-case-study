<?php

namespace App\Repositories;

use App\Contracts\Repositories\TicketRepositoryInterface;
use App\Enums\TicketStatus;
use App\Http\Resources\TicketCollection;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

class TicketRepository implements TicketRepositoryInterface
{
    public function getUserTickets(int $userId): Collection
    {
        $tickets = Ticket::withTrashed()->where("user_id", $userId)->with(["reservation", "seat"])->getAll();
        return $tickets;
    }

    public function getUserTicketById(int $userId, int $ticketId): Ticket
    {
        $ticket = Ticket::withTrashed()->where("user_id", $userId)->with(["reservation", "seat"])->findOrFail($ticketId);
        return $ticket;
    }

    public function createTicket(array $ticketData): Ticket
    {
        $ticket = Ticket::create($ticketData);
        return $ticket;
    }

    public function transferUserTicket(int $userId, int $ticketId, string $email): Ticket
    {
        $ticket = Ticket::withTrashed()->where("user_id", $userId)->lockForUpdate()->findOrFail($ticketId);
        $ticket->updateOrFail(["status" => TicketStatus::TRANSFERRED]);
        return $ticket;
    }
}

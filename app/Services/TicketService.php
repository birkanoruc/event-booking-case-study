<?php

namespace App\Services;

use App\Contracts\Repositories\TicketRepositoryInterface;
use App\Contracts\Services\TicketInterface;
use Illuminate\Support\Facades\Auth;

class TicketService implements TicketInterface
{
    protected TicketRepositoryInterface $ticketRepository;
    protected $userId;

    public function __construct(TicketRepositoryInterface $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->userId = Auth::id();
    }

    public function getTickets()
    {
        return $this->ticketRepository->getUserTickets($this->userId);
    }

    public function getTicketById(int $ticketId)
    {
        return $this->ticketRepository->getUserTicketById($this->userId, $ticketId);
    }

    public function download(int $ticketId)
    {
        $ticket = $this->getTicketById($ticketId);
    }

    public function transfer(int $ticketId, string $email)
    {
        $ticket = $this->getTicketById($ticketId);
    }
}

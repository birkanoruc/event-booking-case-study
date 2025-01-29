<?php

namespace App\Http\Controllers;

use App\Contracts\Services\TicketInterface;
use App\Http\Requests\Ticket\TicketFilterRequest;
use App\Http\Requests\Ticket\TicketTransferRequest;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    /**
     * Biletlerle ilgili iş mantığını gerçekleştiren servis sınıfı.
     * @var TicketInterface
     */
    private TicketInterface $ticketService;

    /**
     * Bilet servisi dependency injection yoluyla sağlanır.
     * @param \App\Contracts\Services\TicketInterface $ticketService
     */
    public function __construct(TicketInterface $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    /**
     * Servis ile biletleri listelemek için bağlantı sağlanır.
     * @return JsonResponse
     */
    public function index(TicketFilterRequest $request): JsonResponse
    {
        return $this->ticketService->getTickets($request->validated());
    }

    /**
     * Servis ile belirli bir bileti getirmek içik bağlantı sağlanır.
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return $this->ticketService->getTicketById($id);
    }

    /**
     * Servis ile belirli bir biletin çıktısını(PDF) getirmek için bağlantı sağlanır.
     * @param int $id
     * @return JsonResponse
     */
    public function download(int $id): JsonResponse
    {
        return $this->ticketService->download($id);
    }

    /**
     * Servis ile bir bileti sistemdeki başka bir kullanıcıya transfer etmek için bağlantı sağlanır.
     * @param \App\Http\Requests\Ticket\TicketTransferRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function transfer(TicketTransferRequest $request, int $id): JsonResponse
    {
        return $this->ticketService->transfer($id, $request->validated());
    }
}

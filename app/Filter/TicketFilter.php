<?php

namespace App\Filter;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class TicketFilter
{
    // Filtreleme parametrelerini tutan değişkenler
    public $reservation_id;
    public $seat_id;
    public $ticket_code;
    public $sort_by;
    public $sort_order;
    public $page;
    public $per_page;

    /**
     * TicketFilter constructor.
     *
     * Filtreleme parametrelerini alır ve varsayılan değerler ile başlatır.
     *
     * @param array $filters
     */
    public function __construct(array $filters)
    {
        $this->reservation_id = $filters['reservation_id'] ?? null;
        $this->seat_id = $filters['seat_id'] ?? null;
        $this->ticket_code = $filters['ticket_code'] ?? null;
        $this->sort_by = $filters['sort_by'] ?? 'start_date';
        $this->sort_order = $filters['sort_order'] ?? 'asc';
        $this->page = $filters['page'] ?? 1;
        $this->per_page = $filters['per_page'] ?? 15;
    }

    /**
     * Uygulanan filtreleri sorguya uygular.
     *
     * @param Builder $query
     * @return Builder
     */
    public function applyFilters(Builder $query): Builder
    {
        if ($this->reservation_id) {
            $query->where('reservation_id', $this->reservation_id);
        }

        if ($this->seat_id) {
            $query->where('seat_id', $this->seat_id);
        }

        if ($this->ticket_code) {
            $query->where('ticket_code', $this->ticket_code);
        }

        return $query->orderBy($this->sort_by, $this->sort_order);
    }

    /**
     * Sayfalama işlemi uygular.
     *
     * @param Builder $query
     * @return LengthAwarePaginator
     */
    public function applyPagination(Builder $query): LengthAwarePaginator
    {
        return $query->paginate($this->per_page);
    }
}

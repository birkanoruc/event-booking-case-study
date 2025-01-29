<?php

namespace App\Filter;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ReservationFilter
{
    // Filtreleme parametrelerini tutan değişkenler
    public $total_amount;
    public $sort_by;
    public $expires_at;
    public $sort_order;
    public $page;
    public $per_page;

    /**
     * ReservationFilter constructor.
     *
     * Filtreleme parametrelerini alır ve varsayılan değerler ile başlatır.
     *
     * @param array $filters
     */
    public function __construct(array $filters)
    {
        $this->total_amount = $filters['total_amount'] ?? null;
        $this->expires_at = $filters['expires_at'] ?? null;
        $this->sort_by = $filters['sort_by'] ?? 'expires_at';
        $this->sort_order = $filters['sort_order'] ?? 'desc';
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

        if ($this->total_amount) {
            $query->where('total_amount', '>=', $this->total_amount);
        }

        if ($this->expires_at) {
            $query->whereDate('expires_at', '=', $this->expires_at);
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

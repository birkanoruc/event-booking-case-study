<?php

namespace App\Filter;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class EventFilter
{
    // Filtreleme parametrelerini tutan değişkenler
    public $start_date;
    public $end_date;
    public $venue_id;
    public $name;
    public $status;
    public $sort_by;
    public $sort_order;
    public $page;
    public $per_page;

    /**
     * EventFilter constructor.
     *
     * Filtreleme parametrelerini alır ve varsayılan değerler ile başlatır.
     *
     * @param array $filters
     */
    public function __construct(array $filters)
    {
        $this->start_date = $filters['start_date'] ?? null;
        $this->end_date = $filters['end_date'] ?? null;
        $this->venue_id = $filters['venue_id'] ?? null;
        $this->name = $filters['name'] ?? null;
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
        if ($this->start_date) {
            $query->where('start_date', '>=', $this->start_date);
        }

        if ($this->end_date) {
            $query->where('end_date', '<=', $this->end_date);
        }

        if ($this->venue_id) {
            $query->where('venue_id', $this->venue_id);
        }

        if ($this->name) {
            $query->where('name', 'like', '%' . $this->name . '%');
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

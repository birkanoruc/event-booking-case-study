<?php

namespace App\Filter;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class SeatFilter
{
    // Filtreleme parametrelerini tutan değişkenler
    public $section;
    public $row;
    public $venue_id;
    public $number;
    public $price;
    public $status;
    public $sort_by;
    public $sort_order;
    public $page;
    public $per_page;

    /**
     * SeatFilter constructor.
     *
     * Filtreleme parametrelerini alır ve varsayılan değerler ile başlatır.
     *
     * @param array $filters
     */
    public function __construct(array $filters)
    {
        $this->section = $filters['section'] ?? null;
        $this->row = $filters['row'] ?? null;
        $this->venue_id = $filters['venue_id'] ?? null;
        $this->number = $filters['number'] ?? null;
        $this->price = $filters['price'] ?? null;
        $this->sort_by = $filters['sort_by'] ?? 'price';
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
        if ($this->section) {
            $query->where('section', 'like', '%' . $this->section . '%');
        }

        if ($this->row) {
            $query->where('row', 'like', '%' . $this->row . '%');
        }

        if ($this->number) {
            $query->where('number', $this->number);
        }

        if ($this->price) {
            $query->where('price', '<=', $this->price);
        }

        if ($this->venue_id) {
            $query->where('venue_id', $this->venue_id);
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

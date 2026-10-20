<?php

namespace App\Livewire\Admin;

use App\Models\LocalSubscription;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class AllPurchases extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 5;

    #[Url]
    public $statusFilter = '';

    #[Url]
    public $amountFilter = '';

    public function updated($v)
    {
        if (in_array($v, ['search', 'statusFilter', 'amountFilter'])) {
            $this->resetPage();
        }
    }

    public function resetFilters()
    {
        $this->reset(
            'statusFilter',
            'amountFilter'
        );
    }

    public function render()
    {
        $purchases = LocalSubscription::query()
            ->with('user', 'plan')
            ->when($this->search, fn ($query) => $query->whereHas('user', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%')))
            ->when($this->statusFilter, fn ($query) => $query->where('status', $this->statusFilter))
            ->when($this->amountFilter, fn ($query) => $query->where('amount_paid', '<=', $this->amountFilter))
            ->latest()
            ->paginate($this->perPage);

        /** @disregard @phpstan-ignore-line */
        return view('livewire.admin.all-purchases', compact('purchases'))
            ->extends('layouts.tailadmin')
            ->section('content');
    }
}

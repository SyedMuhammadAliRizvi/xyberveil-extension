<?php

namespace App\Livewire\Admin;

use App\Models\Plan;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class AllPlans extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 5;

    #[Url]
    public $priceFilter = '';

    #[Url]
    public $durationUnitFilter = '';

    #[Url]
    public $typeFilter = '';

    public Plan $plan;

    public $name;

    public $description;

    public $type = 'regular';

    public $original_price;

    public $discount_price;

    public $currency;

    public $appstore_product_id;

    public $stripe_price_id;

    public $max_devices = null;

    public $is_active = true;

    public $is_best_deal = false;

    public $trial_period = 0;

    public $trial_interval = 'day';

    public $invoice_period = 1;

    public $invoice_interval = 'month';

    public $grace_period = 0;

    public $grace_interval = 'day';

    public $isEdit = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:60',
            'description' => 'required|string',
            'type' => 'required|in:regular,dedicated',
            'original_price' => 'required|numeric|min:0',
            'discount_price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:5',
            'stripe_price_id' => 'nullable|string|max:255',
            'appstore_product_id' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'is_best_deal' => 'required|boolean',
            'trial_period' => 'nullable|integer|min:0',
            'trial_interval' => 'required_if:trial_period,>0|in:day,week,month,year',
            'invoice_period' => 'required|integer|min:1',
            'invoice_interval' => 'required|in:day,week,month,year',
            'grace_period' => 'nullable|integer|min:0',
            'grace_interval' => 'required_if:grace_period,>0|in:day,week,month,year',
            'max_devices' => 'nullable|integer|min:1|max:255',
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'plan',
            'name',
            'description',
            'type',
            'original_price',
            'discount_price',
            'currency',
            'stripe_price_id',
            'appstore_product_id',
            'is_active',
            'is_best_deal',
            'trial_period',
            'trial_interval',
            'invoice_period',
            'invoice_interval',
            'grace_period',
            'grace_interval',
            'max_devices',
            'isEdit',
        ]);
        $this->type = 'regular';
        $this->isEdit = false;
        $this->resetValidation();
    }

    public function editPlan($planId)
    {
        $this->resetForm();
        $this->isEdit = true;

        $plan = Plan::findOrFail($planId);
        $this->plan = $plan;
        $this->name = $plan->name;
        $this->description = $plan->description;
        $this->type = $plan->type;
        $this->original_price = $plan->original_price;
        $this->discount_price = $plan->discount_price;
        $this->currency = $plan->currency;
        $this->appstore_product_id = $plan->appstore_product_id;
        $this->stripe_price_id = $plan->stripe_price_id;
        $this->is_active = $plan->is_active;
        $this->is_best_deal = $plan->is_best_deal;
        $this->trial_period = $plan->trial_period;
        $this->trial_interval = $plan->trial_interval;
        $this->invoice_period = $plan->invoice_period;
        $this->invoice_interval = $plan->invoice_interval;
        $this->grace_period = $plan->grace_period;
        $this->grace_interval = $plan->grace_interval;
        $this->max_devices = $plan->max_devices;
    }

    public function savePlan()
    {
        $this->validate();

        if ($this->isEdit && isset($this->plan)) {
            $this->plan->update([
                'name' => $this->name,
                'description' => $this->description,
                'type' => $this->type,
                'original_price' => $this->original_price,
                'discount_price' => $this->discount_price,
                'currency' => $this->currency,
                'appstore_product_id' => $this->appstore_product_id,
                'stripe_price_id' => $this->stripe_price_id,
                'is_active' => $this->is_active,
                'is_best_deal' => $this->is_best_deal,
                'trial_period' => $this->trial_period,
                'trial_interval' => $this->trial_interval,
                'invoice_period' => $this->invoice_period,
                'invoice_interval' => $this->invoice_interval,
                'grace_period' => $this->grace_period,
                'grace_interval' => $this->grace_interval,
                'max_devices' => $this->max_devices ?: null,
            ]);
            $message = 'Plan updated successfully.';
        } else {
            Plan::create([
                'name' => $this->name,
                'description' => $this->description,
                'type' => $this->type,
                'original_price' => $this->original_price,
                'discount_price' => $this->discount_price,
                'currency' => $this->currency,
                'appstore_product_id' => $this->appstore_product_id,
                'stripe_price_id' => $this->stripe_price_id,
                'is_active' => $this->is_active,
                'is_best_deal' => $this->is_best_deal,
                'trial_period' => $this->trial_period,
                'trial_interval' => $this->trial_interval,
                'invoice_period' => $this->invoice_period,
                'invoice_interval' => $this->invoice_interval,
                'grace_period' => $this->grace_period,
                'grace_interval' => $this->grace_interval,
                'max_devices' => $this->max_devices ?: null,
            ]);
            $message = 'Plan created successfully.';
        }

        $this->dispatch('closeModel');
        $this->dispatch('sweetAlert', title: 'Success!', message: $message, type: 'success');
        $this->resetPage();
        $this->resetForm();
    }

    public function updated($value)
    {
        if (in_array($value, ['search', 'priceFilter', 'durationUnitFilter', 'typeFilter'])) {
            $this->resetPage();
        }
    }

    public function resetFilters()
    {
        $this->reset('priceFilter', 'durationUnitFilter', 'typeFilter', 'search');
    }

    public function render()
    {
        $plans = Plan::query()
            ->withTrashed()
            ->withCount([
                'subscriptions' => fn ($q) => $q->whereIn('status', ['trialing', 'active', 'grace']),
            ])
            ->when($this->search, fn ($query) => $query->where('name', 'like', '%'.$this->search.'%'))
            ->when(
                $this->priceFilter,
                fn ($query) => $query->whereRaw('COALESCE(discount_price, original_price) <= ?', [$this->priceFilter])
            )
            ->when($this->typeFilter, fn ($query) => $query->where('type', $this->typeFilter))
            ->when($this->durationUnitFilter, fn ($query) => $query->where('invoice_interval', $this->durationUnitFilter))
            ->latest('created_at')
            ->paginate($this->perPage);

        /** @disregard @phpstan-ignore-line */
        return view('livewire.admin.all-plans', compact('plans'))
            ->extends('layouts.tailadmin')
            ->section('content');
    }

    public function deletePlan($planId)
    {
        $plan = Plan::findOrFail($planId);
        $plan->delete();

        $this->dispatch('sweetAlert', title: 'Deleted!', message: 'Plan has been deleted successfully.', type: 'success');
    }

    public function restorePlan($planId)
    {
        $plan = Plan::withTrashed()->findOrFail($planId);
        $plan->restore();

        $this->dispatch('sweetAlert', title: 'Restored!', message: 'Plan has been restored successfully.', type: 'success');
    }

    public function forceDeletePlan($planId)
    {
        $plan = Plan::withTrashed()->findOrFail($planId);
        if ($plan->subscriptions()->exists()) {
            $this->dispatch('sweetAlert', title: 'Error!', message: 'Cannot permanently delete a plan with existing subscriptions.', type: 'error');

            return;
        }
        $plan->forceDelete();

        $this->dispatch('sweetAlert', title: 'Permanently Deleted!', message: 'Plan has been permanently deleted.', type: 'success');
    }
}

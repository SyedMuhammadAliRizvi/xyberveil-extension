<?php

namespace App\Livewire\Admin;

use App\Models\PromoCode;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class AllPromoCodes extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 10;

    // Form fields
    public $code;

    public $name;

    public $description;

    public $discount_type = 'percentage';

    public $discount_value;

    public $min_purchase_amount = null;

    public $applicable_plan_ids = null;

    public $max_uses = null;

    public $valid_from = null;

    public $valid_until = null;

    public $is_active = true;

    public $isEdit = false;

    public ?PromoCode $promoCode = null;

    public ?PromoCode $viewingPromo = null;

    // Bulk generation fields
    public string $bulkPrefix = '';

    public int $bulkQuantity = 5;

    public string $bulkName = '';

    public string $bulkDiscountType = 'percentage';

    public $bulkDiscountValue = null;

    public $bulkMinPurchaseAmount = null;

    public $bulkMaxUses = null;

    public $bulkValidFrom = null;

    public $bulkValidUntil = null;

    public bool $bulkIsActive = true;

    protected function rules(): array
    {
        return [
            'code' => 'nullable|string|max:50|alpha_dash',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0.01',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'required|boolean',
        ];
    }

    public function resetForm(): void
    {
        $this->reset([
            'code', 'name', 'description', 'discount_type', 'discount_value',
            'min_purchase_amount', 'applicable_plan_ids', 'max_uses',
            'valid_from', 'valid_until', 'is_active', 'isEdit', 'promoCode',
        ]);
        $this->discount_type = 'percentage';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function resetBulkForm(): void
    {
        $this->reset([
            'bulkPrefix', 'bulkName', 'bulkDiscountValue',
            'bulkMinPurchaseAmount', 'bulkMaxUses', 'bulkValidFrom', 'bulkValidUntil',
        ]);
        $this->bulkQuantity = 5;
        $this->bulkDiscountType = 'percentage';
        $this->bulkIsActive = true;
        $this->resetValidation();
    }

    public function generateCode(): void
    {
        $this->code = strtoupper(Str::random(8));
    }

    public function editPromoCode(int $id): void
    {
        $this->resetForm();
        $this->isEdit = true;
        $promo = PromoCode::findOrFail($id);
        $this->promoCode = $promo;
        $this->code = $promo->code;
        $this->name = $promo->name;
        $this->description = $promo->description;
        $this->discount_type = $promo->discount_type;
        $this->discount_value = $promo->discount_value;
        $this->min_purchase_amount = $promo->min_purchase_amount;
        $this->max_uses = $promo->max_uses;
        $this->valid_from = $promo->valid_from?->format('Y-m-d\TH:i');
        $this->valid_until = $promo->valid_until?->format('Y-m-d\TH:i');
        $this->is_active = $promo->is_active;
    }

    public function savePromoCode(): void
    {
        $this->validate();

        $resolvedCode = $this->code
            ? strtoupper(trim($this->code))
            : $this->generateUniqueCode();

        $data = [
            'code' => $resolvedCode,
            'name' => $this->name,
            'description' => $this->description,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
            'min_purchase_amount' => $this->min_purchase_amount ?: null,
            'max_uses' => $this->max_uses ?: null,
            'valid_from' => $this->valid_from ?: null,
            'valid_until' => $this->valid_until ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->isEdit && $this->promoCode) {
            $this->promoCode->update($data);
            $message = 'Promo code updated successfully.';
        } else {
            PromoCode::create($data);
            $message = 'Promo code created successfully.';
        }

        $this->dispatch('closeModal');
        $this->dispatch('sweetAlert', title: 'Success!', message: $message, type: 'success');
        $this->resetPage();
        $this->resetForm();
    }

    public function generateBulkCodes(): void
    {
        $this->validate([
            'bulkQuantity' => 'required|integer|min:1|max:500',
            'bulkName' => 'required|string|max:255',
            'bulkDiscountType' => 'required|in:percentage,fixed',
            'bulkDiscountValue' => 'required|numeric|min:0.01',
            'bulkMinPurchaseAmount' => 'nullable|numeric|min:0',
            'bulkMaxUses' => 'nullable|integer|min:1',
            'bulkValidFrom' => 'nullable|date',
            'bulkValidUntil' => 'nullable|date|after_or_equal:bulkValidFrom',
        ]);

        $prefix = strtoupper(trim($this->bulkPrefix));
        $created = 0;

        for ($i = 0; $i < $this->bulkQuantity; $i++) {
            PromoCode::create([
                'code' => $this->generateUniqueCode($prefix),
                'name' => $this->bulkName,
                'description' => null,
                'discount_type' => $this->bulkDiscountType,
                'discount_value' => $this->bulkDiscountValue,
                'min_purchase_amount' => $this->bulkMinPurchaseAmount ?: null,
                'max_uses' => $this->bulkMaxUses ?: null,
                'valid_from' => $this->bulkValidFrom ?: null,
                'valid_until' => $this->bulkValidUntil ?: null,
                'is_active' => $this->bulkIsActive,
            ]);
            $created++;
        }

        $this->dispatch('closeBulkModal');
        $this->dispatch('sweetAlert', title: 'Done!', message: "{$created} promo codes generated.", type: 'success');
        $this->resetPage();
        $this->resetBulkForm();
    }

    private function generateUniqueCode(string $prefix = ''): string
    {
        do {
            $code = $prefix.strtoupper(Str::random(8));
        } while (PromoCode::where('code', $code)->exists());

        return $code;
    }

    public function viewUsages(int $id): void
    {
        $this->viewingPromo = PromoCode::with([
            'usages' => fn ($q) => $q->with('user', 'subscription')->latest('used_at'),
        ])->findOrFail($id);

        $this->dispatch('openUsagesModal');
    }

    public function toggleActive(int $id): void
    {
        $promo = PromoCode::find($id);
        if ($promo) {
            $promo->update(['is_active' => ! $promo->is_active]);
            $this->dispatch('sweetAlert', title: 'Updated', message: 'Promo code status toggled.', type: 'success');
        } else {
            $this->dispatch('sweetAlert', title: 'Error', message: 'Promo code not found.', type: 'error');
        }
    }

    public function deletePromoCode(int $id): void
    {
        $code = PromoCode::find($id);
        if ($code) {
            $code->delete();
            $this->dispatch('sweetAlert', title: 'Deleted!', message: 'Promo code deleted.', type: 'success');
            $this->resetPage();
        } else {
            $this->dispatch('sweetAlert', title: 'Error', message: 'Promo code not found.', type: 'error');
        }
    }

    public function updated(string $value): void
    {
        if ($value === 'search') {
            $this->resetPage();
        }
    }

    public function render()
    {
        $promoCodes = PromoCode::query()
            ->when($this->search, fn ($q) => $q->where('code', 'like', '%'.$this->search.'%')
                ->orWhere('name', 'like', '%'.$this->search.'%'))
            ->withCount('usages')
            ->latest()
            ->paginate($this->perPage);

        /** @disregard @phpstan-ignore-line */
        return view('livewire.admin.all-promo-codes', compact('promoCodes'))
            ->extends('layouts.tailadmin')
            ->section('content');
    }
}

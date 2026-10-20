@section('title', 'Promo Codes')
<div x-data>
    <x-tailadmin.breadcrumb :items="[['label' => 'Promo Codes']]" />

    @php
        $cardClass = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]';
        $inputClass = 'h-11 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90';
        $labelClass = 'mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-300';
        $errorClass = 'mt-1 text-theme-xs text-error-500';
    @endphp

    <div class="{{ $cardClass }}">
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Promo Codes</h3>
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" wire:click="resetBulkForm" @click="$dispatch('open-modal-promo-bulk')"
                    class="flex h-10 items-center gap-1.5 rounded-lg border border-gray-200 px-3 text-theme-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                    <iconify-icon icon="mdi:lightning-bolt" width="16" height="16"></iconify-icon>
                    Bulk Generate
                </button>
                <button type="button" wire:click="resetForm" @click="$dispatch('open-modal-promo-form')"
                    class="flex items-center gap-1.5 rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90">
                    <iconify-icon icon="bx:plus" width="18" height="18"></iconify-icon>
                    Create Promo Code
                </button>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            <select wire:model.live="perPage"
                class="h-10 rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            <label class="relative block">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <iconify-icon icon="material-symbols-light:search" width="18" height="18"></iconify-icon>
                </span>
                <input type="text" wire:model.live.500ms="search" placeholder="Search code or name..."
                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 pl-9 pr-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 sm:w-72">
            </label>
        </div>

        <div class="overflow-x-auto border-t border-gray-100 dark:border-gray-800">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr class="text-theme-xs uppercase text-gray-500 dark:text-gray-400">
                        <th class="px-5 py-3 font-medium">Code</th>
                        <th class="px-3 py-3 font-medium">Name</th>
                        <th class="px-3 py-3 font-medium">Discount</th>
                        <th class="px-3 py-3 font-medium">Uses</th>
                        <th class="px-3 py-3 font-medium">Valid Until</th>
                        <th class="px-3 py-3 text-center font-medium">Status</th>
                        <th class="px-5 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($promoCodes as $promo)
                        <tr wire:key="promo-{{ $promo->id }}" class="transition-colors hover:bg-gray-200 dark:hover:bg-white/5">
                            <td class="px-5 py-3"><code class="text-theme-sm font-semibold text-gray-800 dark:text-white/90">{{ $promo->code }}</code></td>
                            <td class="px-3 py-3">
                                <div class="text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $promo->name }}</div>
                                @if ($promo->description)
                                    <div class="text-theme-xs text-gray-400">{{ Str::limit($promo->description, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-3 py-3">
                                @if ($promo->discount_type === 'percentage')
                                    <span class="rounded-full bg-blue-light-50 px-2 py-0.5 text-xs font-medium text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-400">{{ $promo->discount_value }}% off</span>
                                @else
                                    <span class="rounded-full bg-warning-50 px-2 py-0.5 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-400">${{ $promo->discount_value }} off</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">
                                {{ $promo->used_count }}
                                @if ($promo->max_uses)
                                    / {{ $promo->max_uses }}
                                @else
                                    <span class="text-gray-400">/ &infin;</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-theme-sm">
                                @if ($promo->valid_until)
                                    <span class="{{ $promo->valid_until->isPast() ? 'text-error-500' : 'text-gray-600 dark:text-gray-400' }}">{{ $promo->valid_until->format('M d, Y') }}</span>
                                @else
                                    <span class="text-gray-400">No expiry</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-center">
                                @if ($promo->is_active && $promo->isValid())
                                    <span class="rounded-full bg-success-50 px-2 py-0.5 text-xs font-semibold text-success-600 dark:bg-success-500/15 dark:text-success-400">Active</span>
                                @elseif ($promo->is_active && ! $promo->isValid())
                                    <span class="rounded-full bg-warning-50 px-2 py-0.5 text-xs font-semibold text-warning-600 dark:bg-warning-500/15 dark:text-warning-400">Expired/Exhausted</span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-white/5 dark:text-gray-400">Inactive</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <button type="button" wire:click="viewUsages({{ $promo->id }})" title="View usages"
                                        class="flex h-9 items-center gap-1 rounded-lg border border-gray-200 px-2.5 text-theme-xs font-medium text-blue-light-500 transition-colors hover:border-blue-light-300 hover:bg-blue-light-50 dark:border-gray-800 dark:hover:border-blue-light-500/30 dark:hover:bg-blue-light-500/10">
                                        <iconify-icon icon="mdi:account-clock-outline" width="16" height="16"></iconify-icon>
                                        {{ $promo->usages_count }}
                                    </button>
                                    <button type="button" wire:click="editPromoCode({{ $promo->id }})" @click="$dispatch('open-modal-promo-form')" title="Edit"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-warning-500 transition-colors hover:border-warning-300 hover:bg-warning-50 dark:border-gray-800 dark:hover:border-warning-500/30 dark:hover:bg-warning-500/10">
                                        <iconify-icon icon="material-symbols:edit" width="18" height="18"></iconify-icon>
                                    </button>
                                    <button type="button" wire:click="toggleActive({{ $promo->id }})" title="{{ $promo->is_active ? 'Disable' : 'Enable' }}"
                                        class="flex h-9 items-center rounded-lg border border-gray-200 px-2.5 text-theme-xs font-medium text-gray-600 transition-colors hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                                        {{ $promo->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                    <button type="button" wire:click="$js.confirmDelete({{ $promo->id }})" title="Delete"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                        <iconify-icon icon="mingcute:delete-2-line" width="18" height="18"></iconify-icon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-sm text-gray-400">No promo codes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            {{ $promoCodes->links('components.tailadmin.pagination', data: ['scrollTo' => false]) }}
        </div>
    </div>

    {{-- Usages modal --}}
    <x-tailadmin.modal name="promo-usages" max-width="max-w-[900px]">
        <div class="mb-5">
            <h4 class="flex items-center gap-1.5 text-lg font-semibold text-gray-800 dark:text-white/90">
                <iconify-icon icon="mdi:account-clock-outline" width="20" height="20"></iconify-icon>
                Usage History —
                @if ($viewingPromo)
                    <code>{{ $viewingPromo->code }}</code>
                    <span class="text-theme-sm font-normal text-gray-400">{{ $viewingPromo->name }}</span>
                @endif
            </h4>
            @if ($viewingPromo)
                <div class="mt-2 flex items-center gap-3 text-theme-sm text-gray-500 dark:text-gray-400">
                    @if ($viewingPromo->discount_type === 'percentage')
                        <span class="rounded-full bg-blue-light-50 px-2 py-0.5 text-xs font-medium text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-400">{{ $viewingPromo->discount_value }}% off</span>
                    @else
                        <span class="rounded-full bg-warning-50 px-2 py-0.5 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-400">${{ $viewingPromo->discount_value }} off</span>
                    @endif
                    <span>{{ $viewingPromo->usages->count() }} / {{ $viewingPromo->max_uses ?? '∞' }} uses</span>
                </div>
            @endif
        </div>

        @if ($viewingPromo && $viewingPromo->usages->isNotEmpty())
            <div class="max-h-[60vh] overflow-y-auto overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-800">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr class="text-theme-xs uppercase text-gray-500 dark:text-gray-400">
                            <th class="px-3 py-3 font-medium">#</th>
                            <th class="px-3 py-3 font-medium">User</th>
                            <th class="px-3 py-3 font-medium">Plan</th>
                            <th class="px-3 py-3 text-right font-medium">Discount Applied</th>
                            <th class="px-3 py-3 text-right font-medium">Used At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($viewingPromo->usages as $i => $usage)
                            <tr>
                                <td class="px-3 py-3 text-theme-xs text-gray-400">{{ $i + 1 }}</td>
                                <td class="px-3 py-3">
                                    @if ($usage->user)
                                        <div class="text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $usage->user->name }}</div>
                                        <div class="text-theme-xs text-gray-400">{{ $usage->user->email }}</div>
                                    @else
                                        <span class="text-theme-xs text-gray-400">Deleted user</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3">
                                    @if ($usage->subscription?->plan)
                                        <span class="rounded-full bg-brand-50 px-2 py-0.5 text-xs font-medium text-brand-600 dark:bg-brand-500/15 dark:text-brand-400">{{ $usage->subscription->plan->name }}</span>
                                    @else
                                        <span class="text-theme-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-right text-theme-sm font-semibold text-success-600 dark:text-success-400">-${{ number_format($usage->discount_applied, 2) }}</td>
                                <td class="px-3 py-3 text-right text-theme-xs text-gray-500 dark:text-gray-400">
                                    {{ $usage->used_at?->format('M d, Y H:i') }}
                                    <div class="text-gray-400">{{ $usage->used_at?->diffForHumans() }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif ($viewingPromo)
            <div class="py-10 text-center text-gray-400">
                <iconify-icon icon="mdi:ticket-percent-outline" width="48" height="48" class="mx-auto mb-2 opacity-25"></iconify-icon>
                <p>No one has used this promo code yet.</p>
            </div>
        @endif

        <div class="mt-6 flex justify-end border-t border-gray-100 pt-5 dark:border-gray-800">
            <button type="button" @click="$dispatch('close-modal-promo-usages')"
                class="rounded-lg border border-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                Close
            </button>
        </div>
    </x-tailadmin.modal>

    {{-- Bulk generate modal --}}
    <x-tailadmin.modal name="promo-bulk" max-width="max-w-[720px]">
        <h4 class="mb-5 flex items-center gap-1.5 text-lg font-semibold text-gray-800 dark:text-white/90">
            <iconify-icon icon="mdi:lightning-bolt" width="20" height="20"></iconify-icon>
            Bulk Generate Promo Codes
        </h4>

        <form wire:submit.prevent="generateBulkCodes" class="space-y-4">
            <div class="rounded-lg border border-blue-light-200 bg-blue-light-50 px-4 py-3 text-theme-sm text-blue-light-700 dark:border-blue-light-500/30 dark:bg-blue-light-500/10 dark:text-blue-light-400">
                Codes will be generated as <strong>PREFIX + 8 random characters</strong> (e.g. <code>SUMMER-A3BX9QKZ</code>). Leave prefix blank for no prefix.
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $labelClass }}">Prefix <span class="text-gray-400">(optional)</span></label>
                    <input type="text" wire:model.live="bulkPrefix" placeholder="e.g. SUMMER-" class="{{ $inputClass }} uppercase">
                    @if ($bulkPrefix)
                        <div class="mt-1 text-theme-xs text-gray-400">Preview: <code class="text-brand-500">{{ strtoupper(trim($bulkPrefix)) }}XXXXXXXX</code></div>
                    @endif
                </div>
                <div>
                    <label class="{{ $labelClass }}">Quantity <span class="text-error-500">*</span></label>
                    <input type="number" min="1" max="500" wire:model.change="bulkQuantity" placeholder="e.g. 10" class="{{ $inputClass }}">
                    @error('bulkQuantity') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="{{ $labelClass }}">Name <span class="text-error-500">*</span> <span class="text-gray-400">(applied to all codes)</span></label>
                <input type="text" wire:model="bulkName" placeholder="e.g. Summer Sale" class="{{ $inputClass }}">
                @error('bulkName') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $labelClass }}">Discount Type <span class="text-error-500">*</span></label>
                    <select wire:model="bulkDiscountType" class="{{ $inputClass }}">
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount ($)</option>
                    </select>
                </div>
                <div>
                    <label class="{{ $labelClass }}">Discount Value <span class="text-error-500">*</span></label>
                    <input type="number" step="0.01" min="0.01" wire:model="bulkDiscountValue" placeholder="e.g. 25" class="{{ $inputClass }}">
                    @error('bulkDiscountValue') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $labelClass }}">Min Purchase Amount</label>
                    <input type="number" step="0.01" min="0" wire:model="bulkMinPurchaseAmount" placeholder="Leave empty for no minimum" class="{{ $inputClass }}">
                    @error('bulkMinPurchaseAmount') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}">Max Uses <span class="text-gray-400">(per code)</span></label>
                    <input type="number" min="1" wire:model="bulkMaxUses" placeholder="Leave empty for unlimited" class="{{ $inputClass }}">
                    @error('bulkMaxUses') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $labelClass }}">Valid From</label>
                    <input type="datetime-local" wire:model="bulkValidFrom" class="{{ $inputClass }}">
                    @error('bulkValidFrom') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}">Valid Until</label>
                    <input type="datetime-local" wire:model="bulkValidUntil" class="{{ $inputClass }}">
                    @error('bulkValidUntil') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="border-t border-gray-100 pt-4 dark:border-gray-800">
                <x-tailadmin.toggle wire-model="bulkIsActive" label="Active immediately" />
            </div>
        </form>

        <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5 dark:border-gray-800">
            <button type="button" @click="$dispatch('close-modal-promo-bulk')"
                class="rounded-lg border border-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                Cancel
            </button>
            <button type="button" wire:click="generateBulkCodes" wire:loading.attr="disabled" wire:target="generateBulkCodes"
                class="rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90 disabled:opacity-50">
                <span wire:loading.remove wire:target="generateBulkCodes">Generate {{ $bulkQuantity }} Codes</span>
                <span wire:loading wire:target="generateBulkCodes">Generating...</span>
            </button>
        </div>
    </x-tailadmin.modal>

    {{-- Create / Edit modal --}}
    <x-tailadmin.modal name="promo-form" max-width="max-w-[720px]">
        <h4 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">
            {{ $isEdit ? 'Edit Promo Code' : 'Create Promo Code' }}
        </h4>

        <form wire:submit.prevent="savePromoCode" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $labelClass }}">Code <span class="text-gray-400">(leave blank to auto-generate)</span></label>
                    <div class="flex gap-2">
                        <input type="text" wire:model="code" placeholder="e.g. SUMMER25" class="{{ $inputClass }} uppercase">
                        <button type="button" wire:click="generateCode" title="Generate random code"
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                            <iconify-icon icon="mdi:dice-multiple" width="18" height="18"></iconify-icon>
                        </button>
                    </div>
                    @error('code') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}">Name <span class="text-error-500">*</span></label>
                    <input type="text" wire:model.defer="name" placeholder="e.g. Summer Sale 25%" class="{{ $inputClass }}">
                    @error('name') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="{{ $labelClass }}">Description</label>
                <textarea wire:model.defer="description" rows="2" placeholder="Optional description" class="{{ $inputClass }} h-auto py-2.5"></textarea>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $labelClass }}">Discount Type <span class="text-error-500">*</span></label>
                    <select wire:model.defer="discount_type" class="{{ $inputClass }}">
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount ($)</option>
                    </select>
                </div>
                <div>
                    <label class="{{ $labelClass }}">Discount Value <span class="text-error-500">*</span></label>
                    <input type="number" step="0.01" min="0.01" wire:model.defer="discount_value" placeholder="e.g. 25" class="{{ $inputClass }}">
                    @error('discount_value') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $labelClass }}">Min Purchase Amount</label>
                    <input type="number" step="0.01" min="0" wire:model.defer="min_purchase_amount" placeholder="Leave empty for no minimum" class="{{ $inputClass }}">
                    @error('min_purchase_amount') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}">Max Uses</label>
                    <input type="number" min="1" wire:model.defer="max_uses" placeholder="Leave empty for unlimited" class="{{ $inputClass }}">
                    @error('max_uses') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $labelClass }}">Valid From</label>
                    <input type="datetime-local" wire:model.defer="valid_from" class="{{ $inputClass }}">
                    @error('valid_from') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}">Valid Until</label>
                    <input type="datetime-local" wire:model.defer="valid_until" class="{{ $inputClass }}">
                    @error('valid_until') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="border-t border-gray-100 pt-4 dark:border-gray-800">
                <x-tailadmin.toggle wire-model="is_active" label="Active" />
            </div>
        </form>

        <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5 dark:border-gray-800">
            <button type="button" @click="$dispatch('close-modal-promo-form')"
                class="rounded-lg border border-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                Cancel
            </button>
            <button type="button" wire:click="savePromoCode"
                class="rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90">
                {{ $isEdit ? 'Update' : 'Create' }}
            </button>
        </div>
    </x-tailadmin.modal>
</div>

@script
<script>
    $js('confirmDelete', (id) => {
        Swal.fire({
            title: 'Delete Promo Code?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $wire.deletePromoCode(id);
            }
        });
    });

    $wire.on('closeModal', () => {
        window.dispatchEvent(new CustomEvent('close-modal-promo-form'));
    });

    $wire.on('closeBulkModal', () => {
        window.dispatchEvent(new CustomEvent('close-modal-promo-bulk'));
    });

    $wire.on('openUsagesModal', () => {
        window.dispatchEvent(new CustomEvent('open-modal-promo-usages'));
    });

    $wire.on('sweetAlert', (event) => {
        Swal.fire({ title: event.title, text: event.message, icon: event.type, timer: 2000, showConfirmButton: false });
    });
</script>
@endscript

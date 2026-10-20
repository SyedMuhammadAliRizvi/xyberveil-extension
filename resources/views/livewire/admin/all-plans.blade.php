@section('title', 'All Plans')
<div x-data>
    @if (session('message'))
        <x-tailadmin.alert type="info" :message="session('message')" />
    @endif

    <x-tailadmin.breadcrumb :items="[['label' => 'Plans']]" />

    @php
        $cardClass = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]';
        $inputClass = 'h-11 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90';
        $labelClass = 'mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-300';
        $errorClass = 'mt-1 text-theme-xs text-error-500';
    @endphp

    <div class="{{ $cardClass }}">
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">All Plans</h3>
            <button type="button" wire:click="resetForm" @click="$dispatch('open-modal-plan-form')"
                class="flex items-center gap-1.5 rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90">
                <iconify-icon icon="bx:plus" width="18" height="18"></iconify-icon>
                Create Plan
            </button>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            <select wire:model.live="perPage"
                class="h-10 rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>

            <div class="flex items-center gap-2">
                <label class="relative block">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <iconify-icon icon="material-symbols-light:search" width="18" height="18"></iconify-icon>
                    </span>
                    <input type="text" wire:model.live.500ms="search" placeholder="Search..."
                        class="h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 pl-9 pr-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 sm:w-56">
                </label>

                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button type="button" @click="open = !open"
                        class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                        <iconify-icon icon="line-md:filter" width="20" height="20"></iconify-icon>
                    </button>
                    <div x-show="open" x-cloak x-transition @click.stop
                        class="absolute right-0 z-50 mt-2 w-64 rounded-xl border border-gray-200 bg-white p-4 shadow-theme-lg dark:border-gray-800 dark:bg-gray-900">
                        <div class="mb-3 flex items-center justify-between">
                            <h6 class="text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Filters</h6>
                            <button type="button" wire:click="resetFilters" class="text-theme-xs font-medium text-error-500 hover:underline">Reset</button>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Max Price</label>
                                <select wire:model.live="priceFilter"
                                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                    <option value="" selected>Max Price</option>
                                    <option value="10">Under $10</option>
                                    <option value="20">Under $20</option>
                                    <option value="50">Under $50</option>
                                    <option value="100">Under $100</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Type</label>
                                <select wire:model.live="typeFilter"
                                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                    <option value="" selected>Type</option>
                                    <option value="regular">Regular</option>
                                    <option value="dedicated">Dedicated</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Duration Unit</label>
                                <select wire:model.live="durationUnitFilter"
                                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                    <option value="" selected>Duration Unit</option>
                                    <option value="day">Day</option>
                                    <option value="week">Week</option>
                                    <option value="month">Month</option>
                                    <option value="year">Year</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto border-t border-gray-100 dark:border-gray-800">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr class="text-theme-xs uppercase text-gray-500 dark:text-gray-400">
                        <th class="px-5 py-3 font-medium">#</th>
                        <th class="px-3 py-3 font-medium">Name</th>
                        <th class="px-3 py-3 font-medium">Type</th>
                        <th class="px-3 py-3 font-medium">Price</th>
                        <th class="px-3 py-3 font-medium">Billing</th>
                        <th class="px-3 py-3 text-center font-medium">Subscriptions</th>
                        <th class="px-3 py-3 text-center font-medium">Status</th>
                        <th class="px-3 py-3 text-center font-medium">Best Deal</th>
                        <th class="px-3 py-3 font-medium">Created</th>
                        <th class="px-5 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($plans as $plan)
                        <tr wire:key="plan-{{ $plan->id }}" @class(['transition-colors', 'hover:bg-gray-200 dark:hover:bg-white/5', 'opacity-50' => $plan->trashed()])>
                            <td class="px-5 py-3 text-theme-sm text-gray-500 dark:text-gray-400">#PKG-{{ $plan->id }}</td>
                            <td class="px-3 py-3 text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $plan->name }}</td>
                            <td class="px-3 py-3">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $plan->type === 'dedicated' ? 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400' : 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400' }}">
                                    {{ ucfirst($plan->type) }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">
                                @if ($plan->discount_price)
                                    <span class="text-gray-400 line-through">{{ $plan->getCurrencySymbol() }}{{ $plan->original_price }}</span>
                                    <span class="font-semibold text-gray-800 dark:text-white/90">{{ $plan->getCurrencySymbol() }}{{ $plan->discount_price }}</span>
                                @else
                                    {{ $plan->getCurrencySymbol() }}{{ $plan->original_price }}
                                @endif
                            </td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $plan->getBillingLabel() }}</td>
                            <td class="px-3 py-3 text-center text-theme-sm text-gray-600 dark:text-gray-400">{{ $plan->subscriptions_count }}</td>
                            <td class="px-3 py-3 text-center">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $plan->is_active ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400' : 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400' }}">
                                    {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-center">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $plan->is_best_deal ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400' : 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400' }}">
                                    {{ $plan->is_best_deal ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $plan->created_at->format('d M, Y') }}</td>
                            <td class="px-5 py-3">
                                @if ($plan->trashed())
                                    <div class="flex items-center gap-2">
                                        <button type="button" wire:click="restorePlan({{ $plan->id }})" title="Restore"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-success-500 transition-colors hover:border-success-300 hover:bg-success-50 dark:border-gray-800 dark:hover:border-success-500/30 dark:hover:bg-success-500/10">
                                            <iconify-icon icon="mdi:restore" width="18" height="18"></iconify-icon>
                                        </button>
                                        <button type="button" wire:click="$js.confirmForceDelete({{ $plan->id }})" title="Delete Permanently"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                            <iconify-icon icon="mingcute:delete-2-line" width="18" height="18"></iconify-icon>
                                        </button>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2">
                                        <button type="button" wire:click="editPlan({{ $plan->id }})" @click="$dispatch('open-modal-plan-form')" title="Edit"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-warning-500 transition-colors hover:border-warning-300 hover:bg-warning-50 dark:border-gray-800 dark:hover:border-warning-500/30 dark:hover:bg-warning-500/10">
                                            <iconify-icon icon="material-symbols:edit" width="18" height="18"></iconify-icon>
                                        </button>
                                        <button type="button" wire:click="$js.confirmDelete({{ $plan->id }})" title="Delete"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                            <iconify-icon icon="mingcute:delete-2-line" width="18" height="18"></iconify-icon>
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-5 py-10 text-center text-sm text-gray-400">No Plans found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            {{ $plans->links('components.tailadmin.pagination', data: ['scrollTo' => false]) }}
        </div>
    </div>

    {{-- Create / Edit modal --}}
    <x-tailadmin.modal name="plan-form" max-width="max-w-[640px]">
        <h4 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">
            {{ $isEdit ? 'Edit Plan' : 'Add New Plan' }}
        </h4>

        <form wire:submit.prevent="savePlan" class="space-y-4">
            <div class="rounded-lg border border-blue-light-200 bg-blue-light-50 px-4 py-3 text-theme-sm text-blue-light-700 dark:border-blue-light-500/30 dark:bg-blue-light-500/10 dark:text-blue-light-400">
                Please write the period fields (trial, billing, grace) in number of days, weeks, months, or years as appropriate.
            </div>

            <div>
                <label class="{{ $labelClass }}">Name</label>
                <input type="text" wire:model.defer="name" class="{{ $inputClass }}">
                @error('name') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Description</label>
                <textarea wire:model.defer="description" rows="2" class="{{ $inputClass }} h-auto py-2.5"></textarea>
                @error('description') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Type</label>
                <select wire:model.defer="type" class="{{ $inputClass }}">
                    <option value="regular">Regular</option>
                    <option value="dedicated">Dedicated</option>
                </select>
                @error('type') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $labelClass }}">Original Price</label>
                    <input type="number" step="0.01" placeholder="Enter original price" wire:model.defer="original_price" class="{{ $inputClass }}">
                    @error('original_price') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}">Discount Price</label>
                    <input type="number" step="0.01" placeholder="Enter discount price (optional)" wire:model.defer="discount_price" class="{{ $inputClass }}">
                    @error('discount_price') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $labelClass }}">Trial Period</label>
                    <input type="number" placeholder="Enter trial period in days" wire:model.defer="trial_period" class="{{ $inputClass }}">
                    @error('trial_period') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}">Trial Interval</label>
                    <select wire:model.defer="trial_interval" class="{{ $inputClass }}">
                        <option value="day">Day</option>
                        <option value="week">Week</option>
                        <option value="month">Month</option>
                        <option value="year">Year</option>
                    </select>
                    @error('trial_interval') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $labelClass }}">Invoice Period</label>
                    <input type="number" wire:model.defer="invoice_period" class="{{ $inputClass }}">
                    @error('invoice_period') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}">Invoice Interval</label>
                    <select wire:model.defer="invoice_interval" class="{{ $inputClass }}">
                        <option value="day">Day</option>
                        <option value="week">Week</option>
                        <option value="month">Month</option>
                        <option value="year">Year</option>
                    </select>
                    @error('invoice_interval') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $labelClass }}">Grace Period</label>
                    <input type="number" placeholder="Enter grace period in days" wire:model.defer="grace_period" class="{{ $inputClass }}">
                    @error('grace_period') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}">Grace Interval</label>
                    <select wire:model.defer="grace_interval" class="{{ $inputClass }}">
                        <option value="day">Day</option>
                        <option value="week">Week</option>
                        <option value="month">Month</option>
                        <option value="year">Year</option>
                    </select>
                    @error('grace_interval') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="{{ $labelClass }}">Stripe Price ID</label>
                <input type="text" wire:model.defer="stripe_price_id" class="{{ $inputClass }}">
                @error('stripe_price_id') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">App Store Product ID</label>
                <input type="text" wire:model.defer="appstore_product_id" class="{{ $inputClass }}">
                @error('appstore_product_id') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Max Devices <span class="text-gray-400">(leave empty for unlimited)</span></label>
                <input type="number" min="1" max="255" placeholder="e.g. 3" wire:model.defer="max_devices" class="{{ $inputClass }}">
                @error('max_devices') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
            </div>

            <div class="flex flex-wrap gap-x-6 gap-y-3 border-t border-gray-100 pt-4 dark:border-gray-800">
                <x-tailadmin.toggle wire-model="is_active" label="Is Active" />
                <x-tailadmin.toggle wire-model="is_best_deal" label="Is Best Deal" />
            </div>
        </form>

        <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5 dark:border-gray-800">
            <button type="button" wire:click="resetForm" @click="$dispatch('close-modal-plan-form')"
                class="rounded-lg border border-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                Close
            </button>
            <button type="button" wire:click="savePlan"
                class="rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90">
                {{ $isEdit ? 'Update' : 'Save' }}
            </button>
        </div>
    </x-tailadmin.modal>
</div>
@script
    <script>
        $js('confirmDelete', (id) => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.deletePlan(id);
                }
            });
        });

        $js('confirmForceDelete', (id) => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action is irreversible and will permanently delete the plan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it permanently!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.forceDeletePlan(id);
                }
            });
        });

        $wire.on('closeModel', (event) => {
            window.dispatchEvent(new CustomEvent('close-modal-plan-form'));
        });

        $wire.on('sweetAlert', (event) => {
            Swal.fire({
                title: event.title,
                text: event.message,
                icon: event.type,
                timer: 2000,
                showConfirmButton: false
            });
        });
    </script>
@endscript

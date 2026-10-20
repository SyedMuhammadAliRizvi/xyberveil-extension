@section('title', 'Create Server')
<div>
    <x-tailadmin.breadcrumb :items="[
        ['label' => 'VPN Servers', 'route' => 'servers.all'],
        ['label' => 'Create'],
    ]" />

    @php
        $cardClass = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]';
        $inputClass = 'h-11 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90';
        $labelClass = 'mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-300';
        $errorClass = 'mt-1 text-theme-xs text-error-500';
    @endphp

    <div class="{{ $cardClass }} max-w-3xl">
        <div class="px-5 py-4 sm:px-6">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Create Server</h3>
        </div>
        <div class="border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
            <form wire:submit.prevent="store" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="{{ $labelClass }}">Image</label>
                    <x-filepond::upload wire:model="image" allowImageValidateSize="true" maxFileSize="20MB"
                        allowFileTypeValidation="true" acceptedFileTypes="image/jpeg, image/png, image/jpg" />
                    @error('image') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">Name</label>
                    <input type="text" placeholder="Name" wire:model.defer="name" class="{{ $inputClass }}">
                    @error('name') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="{{ $labelClass }}">Type</label>
                        <select wire:model="type" class="{{ $inputClass }}">
                            <option value="" selected>Select Type</option>
                            <option value="free">Free</option>
                            <option value="premium">Premium</option>
                        </select>
                        @error('type') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Status</label>
                        <select wire:model="status" class="{{ $inputClass }}">
                            <option value="" selected>Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        @error('status') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="{{ $labelClass }}">Longitude</label>
                        <input type="text" placeholder="Longitude" wire:model.defer="longitude" class="{{ $inputClass }}">
                        @error('longitude') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Latitude</label>
                        <input type="text" placeholder="Latitude" wire:model.defer="latitude" class="{{ $inputClass }}">
                        @error('latitude') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4 dark:border-gray-800">
                    <label class="{{ $labelClass }}">Platforms</label>
                    <div class="flex flex-wrap gap-x-6 gap-y-3">
                        <x-tailadmin.toggle wire-model="android" label="Android" />
                        <x-tailadmin.toggle wire-model="ios" label="iOS" />
                        <x-tailadmin.toggle wire-model="macos" label="macOS" />
                        <x-tailadmin.toggle wire-model="windows" label="Windows" />
                    </div>
                </div>

                <div>
                    <label class="{{ $labelClass }}">Server Use Cases</label>
                    <div class="flex flex-wrap gap-x-6 gap-y-3">
                        <x-tailadmin.toggle wire-model="gaming" label="Gaming" />
                        <x-tailadmin.toggle wire-model="streaming" label="Streaming" />
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-5 dark:border-gray-800">
                    <button type="submit"
                        class="rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
    @filepondScripts
@endsection

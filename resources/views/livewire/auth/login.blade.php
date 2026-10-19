@section('title', 'Login')
<div>
    <h2 class="text-xl font-bold text-center text-slate-900">Welcome Back</h2>
    <p class="mt-1 mb-6 text-sm text-center text-slate-400">Sign in to your account to continue</p>

    @if ($errors->has('message'))
        <x-tailadmin.alert type="danger" :message="$errors->first('message')" />
    @endif

    <form wire:submit.prevent="login" class="space-y-5">
        <div>
            <label class="block mb-1.5 text-xs font-bold tracking-wide text-slate-500 uppercase">Email</label>
            <input type="email" wire:model="email" placeholder="jhon@example.com" autofocus
                class="w-full rounded-lg border border-slate-200 bg-sky-50/60 px-4 py-3 text-slate-800 focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400">
            @error('email') <p class="mt-1 text-theme-xs text-error-500">{{ $message }}</p> @enderror
        </div>

        <div x-data="{ show: false }">
            <label class="block mb-1.5 text-xs font-bold tracking-wide text-slate-500 uppercase">Password</label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" wire:model="password" placeholder="Enter Password"
                    class="w-full rounded-lg border border-slate-200 bg-sky-50/60 px-4 py-3 pr-12 text-slate-800 focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400">
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 hover:text-slate-600">
                    <i class="fa-regular text-[16px]" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            @error('password') <p class="mt-1 text-theme-xs text-error-500">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-center gap-2 text-xs font-bold tracking-wide text-slate-500 uppercase">
            <input type="checkbox" wire:model="remember" class="rounded border-slate-300 text-brand-500 focus:ring-brand-400">
            Remember me
        </label>

        <button type="submit" wire:loading.attr="disabled" wire:target="login"
            class="w-full rounded-lg bg-brand-500 py-3 font-bold uppercase tracking-wide text-white transition hover:bg-brand-600 disabled:cursor-not-allowed disabled:opacity-70">
            <span wire:loading.remove wire:target="login">Login</span>
            <span wire:loading wire:target="login">
                <i class="fa-solid fa-circle-notch fa-spin"></i>
            </span>
        </button>

        <a href="#" class="block text-center text-sm text-slate-500 hover:text-brand-600">Forgot your password?</a>
    </form>
</div>

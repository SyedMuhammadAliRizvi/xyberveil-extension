import './bootstrap';
import Alpine from 'alpinejs';

if (!window.Alpine) {
    window.Alpine = Alpine;
}

// Pages that render a Livewire component get their own bundled Alpine
// auto-injected by Livewire (which unconditionally takes over window.Alpine
// and calls .start() itself on DOMContentLoaded) — starting ours too would
// create a second, uncoordinated Alpine instance and break wire:model/wire:click.
// Pages with no Livewire component never get that script, so we start our own.
document.addEventListener('DOMContentLoaded', () => {
    if (!window.Livewire) {
        window.Alpine.start();
    }
});

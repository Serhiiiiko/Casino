@php
    // This is a *different* resource altogether
    // If you have a Setting model instance, pass it here; otherwise null is fine
    $sidebar = \App\Filament\Resources\SettingResource::sidebar(null);
@endphp

<x-filament-page-with-sidebar::page :sidebar="$sidebar">
    <form wire:submit="submit">
        {{ $this->form }}
        <x-filament-panels::form.actions :actions="$this->getFormActions()" />
    </form>
</x-filament-page-with-sidebar::page>

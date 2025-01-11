@php
    $sidebar = \App\Filament\Resources\UserResource::sidebar($record);
@endphp

<x-filament-page-with-sidebar::page>
    @include($this->getIncludedSidebarView())
</x-filament-page-with-sidebar::page>
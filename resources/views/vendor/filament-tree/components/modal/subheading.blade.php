<x-filament::modal.description
    :attributes="\Filament\Support\prepare_inherited_attributes($attributes)"
    :dark-mode="\Filament\Facades\Filament::hasDarkMode()"
>
    {{ $slot }}
</x-filament::modal.description>

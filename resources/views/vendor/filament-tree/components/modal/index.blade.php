@captureSlots([
    'actions',
    'content',
    'footer',
    'header',
    'heading',
    'subheading',
    'trigger',
])

<x-filament::modal
    :attributes="\Filament\Support\prepare_inherited_attributes($attributes)->merge($slots)"
    :dark-mode="\Filament\Facades\Filament::hasDarkMode()"
    heading-component="filament-tree::modal.heading"
    {{-- hr-component="tables::hr" --}}
    subheading-component="filament-tree::modal.subheading"
>
    {{ $slot }}
</x-filament::modal>

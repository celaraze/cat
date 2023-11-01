<x-filament-tree::actions.action
    :action="$action"
    dynamic-component="filament::dropdown.list.item"
    :icon="$getGroupedIcon()"
    class="filament-grouped-action"
>
    {{ $getLabel() }}
</x-filament-tree::actions.action>

<x-filament-tree::actions.action
    :action="$action"
    dynamic-component="filament::link"
    :icon-position="$getIconPosition()"
    :icon-size="$getIconSize()"
    class="filament-tree-link-action"
>
    {{ $getLabel() }}
</x-filament-tree::actions.action>

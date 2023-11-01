@props(['optionValue', 'item', 'parent' => null])
@php
    $optionLabel = $item['label'] ?? null;
    $children = $item['children'] ?? [];
    $key = "{$this->id}." . $getStatePath() . '.' . $field::class . '.options.' . $optionValue;
@endphp
<div wire:key="{{ $key }}" x-data="{

    collapsed: false,

    init: function () {
        this.collapsed = this.collapsedAll
        
        $watch('collapsedAll', (value, oldValue) => {
            this.toggleCollapsed()
        })
    },
    
    toggleCollapsed: function () {
        this.collapsed = !this.collapsed
    },

    toggleParentCheckbox: function (el) {
        if (el.target.checked) {
            key = el.target.getAttribute('data-parent-key')
            this.treeOptions.forEach((checkboxLabel) => {
                checkbox = checkboxLabel.querySelector('input[type=checkbox]')
    
                if (key && checkbox.value == key) {
                    console.log(checkbox.value, key)
                    checkbox.checked = true
                    checkbox.dispatchEvent(new Event('change'))
                    checkbox.dispatchEvent(new Event('click'))
                }
            })
        }
    }
}">
    <div class="flex gap-1">
        <button type="button" x-on:click="toggleCollapsed()" x-cloak>
            <x-heroicon-o-minus class="text-gray-400 w-4 h-4 border" x-show="! collapsed" />
            <x-heroicon-o-plus class="text-gray-400 w-4 h-4 border" x-show="collapsed" />
        </button>
        <label class="filament-forms-tree-component-option-label flex items-center space-x-3 rtl:space-x-reverse">
            <input 
                data-parent-key="{{ $parent }}"
                x-on:click="toggleParentCheckbox"
                x-on:change="checkIfAllCheckboxesAreChecked()"
                wire:loading.attr="disabled"
                type="checkbox"
                value="{{ $optionValue }}"
                dusk="filament.forms.{{ $getStatePath() }}"
                {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}"
                {{ $getExtraAttributeBag()->class([
                    'text-primary-600 transition duration-75 rounded shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 disabled:opacity-70',
                    'dark:bg-gray-700 dark:checked:bg-primary-500' => config('forms.dark_mode'),
                    'border-gray-300' => ! $errors->has($getStatePath()),
                    'dark:border-gray-600' => (! $errors->has($getStatePath())) && config('forms.dark_mode'),
                    'border-danger-300 ring-danger-500' => $errors->has($getStatePath()),
                    'dark:border-danger-400 dark:ring-danger-400' => $errors->has($getStatePath()) && config('forms.dark_mode'),
                ])->merge([
                    'disabled' => $isDisabled(),
                ]) }}
            />

            <span @class([
                'filament-forms-tree-component-option-label-text text-sm font-medium text-gray-700',
                'dark:text-gray-200' => config('forms.dark_mode'),
            ])>
                {{ $optionLabel }}
            </span>
        </label>
    </div>
    @if (count($children))
        @foreach ($children as $childValue => $childItem)
            <div class="filament-forms-tree-component-children ml-4 gap-1"
                x-show="! collapsed"
            >
                @include('filament-tree::forms.tree.option-item', ['optionValue' => $childValue, 'item' => $childItem, 'parent' => $optionValue])
            </div>
        @endforeach
    @endif
</div>
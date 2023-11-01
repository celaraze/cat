<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-action="$getHintAction()"
    :hint-color="$getHintColor()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <div {{ $attributes->merge($getExtraAttributes())->class([
            'filament-forms-tree-component py-2 px-5 bg-white border border-gray-300 rounded-xl shadow-sm',
            'dark:bg-gray-500/10' => config('forms.dark_mode'),
        ]) }}
        wire:ignore 
        x-data="{

            areAllCheckboxesChecked: false,

            treeOptions: Array.from($root.querySelectorAll('.filament-forms-tree-component-option-label')),

            collapsedAll: false,

            init: function () {

                this.checkIfAllCheckboxesAreChecked()

                Livewire.hook('message.processed', () => {
                    this.checkIfAllCheckboxesAreChecked()
                })
            },

            checkIfAllCheckboxesAreChecked: function () {
                this.areAllCheckboxesChecked = this.treeOptions.length === this.treeOptions.filter((checkboxLabel) => checkboxLabel.querySelector('input[type=checkbox]:checked')).length
            },

            toggleAllCheckboxes: function () {
                state = ! this.areAllCheckboxesChecked

                this.treeOptions.forEach((checkboxLabel) => {
                    checkbox = checkboxLabel.querySelector('input[type=checkbox]')

                    checkbox.checked = state
                    checkbox.dispatchEvent(new Event('change'))
                })

                this.areAllCheckboxesChecked = state
            },

            toggleCollapseAll: function () {
                this.collapsedAll = ! this.collapsedAll
            }
        }">

        <div
            x-cloak
            class="flex gap-2 mb-2"
            wire:key="{{ $this->id }}.{{ $getStatePath() }}.{{ $field::class }}.buttons"
        >
            <x-filament::link
                tag="button"
                size="sm"
                x-show="! areAllCheckboxesChecked"
                x-on:click="toggleAllCheckboxes()"
                wire:loading.attr="disabled"
                wire:key="{{ $this->id }}.{{ $getStatePath() }}.{{ $field::class }}.buttons.select_all"
            >
                {{ __('filament-tree::filament-tree.components.tree.buttons.select_all.label') }}
            </x-filament::link>

            <x-filament::link
                tag="button"
                size="sm"
                x-show="areAllCheckboxesChecked"
                x-on:click="toggleAllCheckboxes()"
                wire:loading.attr="disabled"
                wire:key="{{ $this->id }}.{{ $getStatePath() }}.{{ $field::class }}.buttons.deselect_all"
            >
                {{ __('filament-tree::filament-tree.components.tree.buttons.deselect_all.label') }}
            </x-filament::link>

            <x-filament::icon-button
                size="sm"
                icon="heroicon-o-plus"
                color="secondary"
                label="{{ __('filament-tree::filament-tree.components.tree.buttons.expand_all.label') }}"
                x-on:click="toggleCollapseAll()"
                wire:loading.attr="disabled"
                wire:key="{{ $this->id }}.{{ $getStatePath() }}.{{ $field::class }}.buttons.expand_all"
            />

            <x-filament::icon-button
                size="sm"
                icon="heroicon-o-minus"
                color="secondary"
                label="{{ __('filament-tree::filament-tree.components.tree.buttons.collapse_all.label') }}"
                x-on:click="toggleCollapseAll()"
                wire:loading.attr="disabled"
                wire:key="{{ $this->id }}.{{ $getStatePath() }}.{{ $field::class }}.buttons.collapse_all"
            />
        </div>

        <x-filament::grid
            :default="$getColumns('default')"
            :sm="$getColumns('sm')"
            :md="$getColumns('md')"
            :lg="$getColumns('lg')"
            :xl="$getColumns('xl')"
            :two-xl="$getColumns('2xl')"
            class="gap-1"
        >
            @foreach ($getOptions() as $optionValue => $item)
                @include('filament-tree::forms.tree.option-item', ['optionValue' => $optionValue, 'item' => $item])
            @endforeach
        </x-filament::grid>
    </div>
</x-dynamic-component>

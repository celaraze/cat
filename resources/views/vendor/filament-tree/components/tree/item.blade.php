@php use Illuminate\Database\Eloquent\Model; @endphp
@php use Filament\Facades\Filament; @endphp
@php use SolutionForest\FilamentTree\Components\Tree; @endphp
@props([
    'record',
    'containerKey',
    'tree',
    'title' => null,
    'icon' => null,
])
@php
    /** @var $record Model */
    /** @var $containerKey string */
    /** @var $tree Tree */

    $recordKey = $tree->getRecordKey($record);
    $parentKey = $tree->getParentKey($record);

    $children = $record->children;
    $collapsed = $this->getNodeCollapsedState($record);

    $actions = $tree->getActions();
@endphp

<li class="filament-tree-row dd-item" data-id="{{ $recordKey }}">
    <div wire:loading.remove.delay
         wire:target="{{ implode(',', Tree::LOADING_TARGETS) }}"
        @class([
            'bg-white rounded-lg border border-gray-300 dark:border-white/10 dd-handle h-10',
            'mb-2',
            'flex w-full items-center ',
            'dark:bg-gray-900' => Filament::hasDarkMode(),
        ])>

        <button type="button"
                class="h-full flex items-center dark:bg-black/30 rounded-l-lg border-r border-gray-300 dark:border-white/10 px-px">
            <x-heroicon-m-ellipsis-vertical class="text-gray-400 dark:text-gray-500 w-4 h-4 -mr-2"/>
            <x-heroicon-m-ellipsis-vertical class="text-gray-400 dark:text-gray-500 w-4 h-4"/>
        </button>

        <div class="dd-content dd-nodrag flex gap-1">
            @if ($icon)
                <div class="w-4">
                    <x-dynamic-component :component="$icon" class="w-4 h-4"/>
                </div>
            @endif

            <span @class([
                'ml-4' => !$icon,
                'font-semibold'
            ])>
                {{ $title }}
            </span>

            <div @class([
                'dd-item-btns',
                'hidden' => ! count($children),
                'flex items-center justify-center pl-3'
            ])>
                <button data-action="expand" @class(['hidden' => ! $collapsed])>
                    <x-heroicon-m-chevron-down class="text-gray-400 dark:text-gray-400 w-5 h-5"/>
                </button>
                <button data-action="collapse" @class(['hidden' => $collapsed])>
                    <x-heroicon-m-chevron-up class="text-gray-400 dark:text-gray-400 w-5 h-5"/>
                </button>
            </div>
        </div>

        @if (count($actions))
            <div class="dd-nodrag ml-auto px-2">
                <x-filament-tree::actions :actions="$actions" :record="$record"/>
            </div>
        @endif
    </div>
    @if (count($children))
        <x-filament-tree::tree.list :records="$children" :containerKey="$containerKey" :tree="$tree"
                                    :collapsed="$collapsed"/>
    @endif
    <div class="rounded-lg border border-gray-300 mb-2 w-full px-4 py-4 animate-pulse hidden"
         wire:loading.class.remove.delay="hidden"
         wire:target="{{ implode(',', Tree::LOADING_TARGETS) }}">
        <div class="h-4 bg-gray-300 rounded-md"></div>
    </div>
</li>

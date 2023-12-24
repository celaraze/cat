<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="flex items-center gap-x-3">
            <div class="flex-1">
                <h2 class="grid flex-1 text-base font-semibold leading-6 text-gray-950 dark:text-white">

                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $this->getDescription() }}
                </p>
            </div>

            <div>
                {{ $this->changeAvatarAction }}
                <x-filament-actions::modals/>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

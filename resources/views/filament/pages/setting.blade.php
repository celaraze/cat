<x-filament-panels::page>
    <section class="">
        <header class="flex items-center gap-x-3 overflow-hidden py-4">
            <div class="grid flex-1 gap-y-1">
                <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    站点名称
                </h3>

                <p class="fi-section-header-description text-sm text-gray-500 dark:text-gray-400">
                    {{ __('themes::themes.select_base_color') }}
                </p>
            </div>
        </header>

        <div class="flex items-center gap-4 border-t py-6">

        </div>
    </section>

    <section class="">
        <header class="flex items-center gap-x-3 overflow-hidden py-4">
            <div class="grid flex-1 gap-y-1">
                <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    {{ __('themes::themes.themes') }}
                </h3>

                <p class="fi-section-header-description text-sm text-gray-500 dark:text-gray-400">
                    {{ __('themes::themes.select_interface') }}
                </p>
            </div>
        </header>
    </section>
</x-filament-panels::page>

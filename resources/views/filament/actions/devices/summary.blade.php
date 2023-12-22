<x-filament-panels::page>
    <table class="w-full table-auto">
        <thead>
        <tr>
            <th colspan="5" class="px-4 py-2 text-left text-xl font-medium uppercase tracking-wider">
                {{ $device->asset_number }}
            </th>
        </tr>
        <tr>
            <th colspan="5" class="px-4 py-2 text-left text-sm font-medium uppercase tracking-wider">
                {{ $device->name }}，
                @if($device->users()->first())
                    {{ $device->users()->first()->getAttribute('name') }} 使用中
                @else
                    无人使用
                @endif
            </th>
        </tr>
        <tr>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">类型</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">品牌</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">名称</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">规格</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">资产编号</th>
        </tr>
        </thead>
        <tbody>
        @foreach($device->parts()->get() as $part)
            <tr>
                <td class="px-4 py-2 text-xs whitespace-nowrap">
                    {{ $part->category?->name }}
                </td>
                <td class="px-4 py-2 text-xs whitespace-nowrap">
                    {{ $part->brand?->name }}
                </td>
                <td class="px-4 py-2 text-xs whitespace-nowrap">

                </td>
                <td class="px-4 py-2 text-xs whitespace-nowrap">
                    {{ $part->specification }}
                </td>
                <td class="px-4 py-2 text-xs whitespace-nowrap">
                    {{ $part->asset_number }}
                </td>
            </tr>
        @endforeach
        @foreach($device->software()->get() as $software)
            <tr>
                <td class="px-4 py-2 text-xs whitespace-nowrap">
                    {{ $software->category?->name }}
                </td>
                <td class="px-4 py-2 text-xs whitespace-nowrap">
                    {{ $software->brand?->name }}
                </td>
                <td class="px-4 py-2 text-xs whitespace-nowrap">
                    {{ $software->name }}
                </td>
                <td class="px-4 py-2 text-xs whitespace-nowrap">
                    {{ $software->specification }}
                </td>
                <td class="px-4 py-2 text-xs whitespace-nowrap">
                    {{ $software->asset_number }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-3 py-3 rounded relative" role="alert">
        <span class="block sm:inline">本速览仅用于快速查看设备附属资产，详请可单独前往设备资产查看。</span>
    </div>
</x-filament-panels::page>

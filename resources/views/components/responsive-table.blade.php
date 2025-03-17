@props(['striped' => false, 'hover' => true])

<div
    class="card bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
    <div class="overflow-x-auto custom-scrollbar p-1">
        <table {{ $attributes->merge(['class' => 'responsive-table w-full']) }}>
            {{ $slot }}
        </table>
    </div>
</div>

@props(['class' => ''])

<td {{ $attributes->merge(['class' => 'px-4 py-3 text-sm text-gray-800 dark:text-gray-200 ' . $class]) }}>
    {{ $slot }}
</td>

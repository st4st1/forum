@props(['active' => false])

@php
$classes = $active
    ? 'bg-gray-100 text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md'
    : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
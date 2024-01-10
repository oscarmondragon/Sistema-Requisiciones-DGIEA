@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-verde text-sm font-medium leading-5 text-textos_generales focus:outline-none focus:border-verde transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-textos_generales hover:text-gray-700 hover:border-verde focus:outline-none focus:text-gray-700 focus:border-verde transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

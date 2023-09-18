@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'container mx-auto border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-full shadow-sm']) !!}>
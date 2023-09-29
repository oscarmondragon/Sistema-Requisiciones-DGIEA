@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'container mx-auto border-gray-300 focus:border-verde focus:ring-2 focus:ring-[#3c5042] rounded-full shadow-sm']) !!}>
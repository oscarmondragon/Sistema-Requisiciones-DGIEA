@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm text-textos-generales']) }}>
    {{ $value ?? $slot }}
</label>

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-success']) }}>
    {{ $slot }}
</button>

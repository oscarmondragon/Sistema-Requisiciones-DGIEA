<x-cvu-layout>

    @include('layouts.header-cvu')

    @if ($accion == 1)
    <livewire:crear-requisicion />
    @endif
    @if ($accion == 2)
    <livewire:vistos-buenos />
    @endif
    @if ($accion == 3)
    <livewire:seguimiento />
    @endif
    </div>
    </div>
    </div>

</x-cvu-layout>
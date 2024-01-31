<section>
    <header>
        <h2 class="text-lg font-medium text-dorado">
            {{ __('Información del perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Actualice la información del perfil y la dirección de correo electrónico de su cuenta.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nombre:')" />
            <x-text-input id="name" name="name" type="text"
                class="mt-1 block w-full bg-blanco text-textos_generales" :value="old('name', $user->name)" placeholder="Nombre"
                autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="sm:grid grid-cols-2 gap-x-4">
            <div>
                <x-input-label for="apePaterno" :value="__('Apellido paterno:')" />
                <x-text-input id="apePaterno" name="apePaterno" type="text"
                    class="mt-1 block w-full bg-blanco text-textos_generales" :value="old('apePaterno', $user->apePaterno)"
                    placeholder="Apellido paterno" autocomplete="apePaterno" />
                <x-input-error class="mt-2" :messages="$errors->get('apePaterno')" />
            </div>

            <div>
                <x-input-label class="sm:mt-0 mt-4" for="apeMaterno" :value="__('Apellido materno:')" />
                <x-text-input id="apeMaterno" name="apeMaterno" type="text"
                    class="mt-1 block w-full bg-blanco text-textos_generales" :value="old('apeMaterno', $user->apeMaterno)"
                    placeholder="Apellido materno" autocomplete="apeMaterno" />
                <x-input-error class="mt-2" :messages="$errors->get('apeMaterno')" />
            </div>
        </div>

        <div>
            <x-input-label for="email" :value="__('Correo electrónico:')" />
            <x-text-input id="email" name="email" type="email"
                class="mt-1 block w-full bg-blanco text-textos_generales" :value="old('email', $user->email)"
                placeholder="Correo electrónico" autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Su dirección de correo electrónico no está verificada.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Haga clic aquí para volver a enviar el correo electrónico de verificación.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Se ha enviado un nuevo enlace de verificación a su dirección de correo electrónico.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-secondary-button class="mx-auto rounded-full hover:rounded-full sm:w-auto w-full">
                {{ __('Guardar cambios') }}
            </x-secondary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Guardar cambios') }}</p>
            @endif
        </div>
    </form>
</section>

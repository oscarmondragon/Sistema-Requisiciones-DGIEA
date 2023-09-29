<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <img src="img/fondo.png" alt="image/png" class="static w-52 -mt-52 -ml-6">
        </div>

        <div class="mx-auto w-3/4 block mt-12">
            <!-- Email Address -->
            <div class="my-4">
                <!-- <x-input-label for="email" class="text-white" :value="__('Correo electrónico')" /> -->
                <!-- <i class="fa-solid fa-envelope fa-2xl absolute mr-10 ml-4 mt-1 text-3xl text-white/70"></i> -->
                <span>
                    <img src="{{ asset('img/ic_email.png') }}" alt="Icono" class="ic_login">
                </span>
                <x-text-input id="email" class="input_login" type="email" name="email" :value="old('email')" placeholder="Correo electrónico" required autofocus autocomplete="username" />
                <!-- <x-input-error :messages="$errors->get('email')" class="mt-2" /> -->
            </div>

            <!-- Password -->
            <div>
                <!-- <x-input-label for="password" :value="__('Contraseña')" /> -->
                <!-- <i class="fa-solid fa-lock fa-2xl absolute mr-10 ml-4 mt-1 text-3xl text-white/70"></i> -->
                <span>
                    <img src="{{ asset('img/ic_lock.png') }}" alt="Icono" class="ic_login">
                </span>
                <x-text-input id="password" class="input_login" type="password" name="password" placeholder="Contraseña" required autocomplete="current-password" />
                @error('email') <span class="text-rojo block text-center mt-4">{{ $message }}</span> @enderror
                <!-- <x-input-error :messages="$errors->get('password')" class="mt-2" /> -->
            </div>

            <!-- Remember Me -->
            <!-- <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Recordarme') }}</span>
            </label>
            </div> -->

            <!-- <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif  -->


            <x-primary-button class="my-4 bg-white/50 rounded-full">
                {{ __('Iniciar sesión') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
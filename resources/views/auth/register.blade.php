<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mt-4">
            <!-- <x-input-label for="name" :value="__('Name')" /> -->
            <x-text-input id="name" class="block mt-1 w-full input_login" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nombre" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Apellido Paterno -->
        <div class="mt-4">
            <!-- <x-input-label for="apePaterno" :value="__('Apellido Paterno')" /> -->
            <x-text-input id="apePaterno" class="block mt-1 w-full input_login" type="text" name="apePaterno" :value="old('apePaterno')" required autofocus autocomplete="name" placeholder="Apellido paterno" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Apellido Materno -->
        <div class="mt-4">
            <!-- <x-input-label for="apeMaterno" :value="__('Apellido Materno')" /> -->
            <x-text-input id="apeMaterno" class="block mt-1 w-full input_login" type="text" name="apeMaterno" :value="old('apeMaterno')" required autofocus autocomplete="name" placeholder="Apellido materno" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <!-- <x-input-label for="email" :value="__('Email')" /> -->
            <x-text-input id="email" class="block mt-1 w-full input_login" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Correo electrónico" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <!-- <x-input-label for="password" :value="__('Password')" /> -->
            <x-text-input id="password" class="block mt-1 w-full input_login" type="password" name="password" required autocomplete="new-password" placeholder="Contraseña" />

            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <!-- <x-input-label for="password_confirmation" :value="__('Confirm Password')" /> -->
            <x-text-input id="password_confirmation" class="block mt-1 w-full input_login" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirmar contraseña" />

            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <!-- Rol usuario -->
        <div class="mt-4">
            <x-input-label class="inline-block" for="rol" :value="__('Rol de usuario:')" />
            <select name="rol_usuario" id="rol_usuario" wire:model="rol_usuario" required class="w-3/4 rounded-full input_login inline-block text-center">
                <option value="">Roles de usuario</option>
                <option value="1">Admin</option>
                <option value="2">Revisor</option>
            </select> 
        </div>

        <x-primary-button class="mt-4 bg-white/50 rounded-full">
            {{ __('Registrar') }}
        </x-primary-button>

        <!-- <div class="text-center mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verde" href="{{ route('login') }}">
                {{ __('Ya tienes cuenta?') }}
            </a>
        </div> -->
    </form>
</x-guest-layout>
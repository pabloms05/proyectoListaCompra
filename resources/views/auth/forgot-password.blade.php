<x-guest-layout>
    <style>
        html,
        body {
            overflow: hidden;
        }
    </style>
    <x-slot name="navbar">
        <div class="flex items-center space-x-4">
            <!-- Botón Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 bg-white/10 text-white font-semibold rounded-2xl backdrop-blur-xl hover:bg-white/20 transition-colors flex items-center space-x-2">
                🏠 <span>Inicio</span>
            </a>
        </div>
    </x-slot>

    <div class="w-full min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-6 text-white flex flex-col justify-center">

            <!-- Título -->
            <h2 class="text-3xl font-semibold tracking-wide mb-4 text-center">Recuperar Contraseña</h2>

            <!-- Mensaje informativo -->
            <p class="text-sm text-gray-300 mb-4 text-center">
                Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
            </p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Formulario -->
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold mb-2">Email</label>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus
                        class="w-full rounded-xl border border-white/20 bg-white/10 text-white placeholder-gray-300 px-4 py-2 focus:border-purple-400 focus:ring-purple-400 transition">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-400" />
                </div>

                <!-- Botón enviar -->
                <div class="mb-4">
                    <button type="submit"
                        class="w-full px-5 py-3 bg-purple-600 hover:bg-purple-700 border border-white/20 rounded-xl font-semibold shadow-md transition">
                        Enviar enlace de recuperación
                    </button>
                </div>
            </form>

            <!-- Botón volver -->
            <div class="text-center">
                <a href="{{ route('login') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-700/60 border border-white/20 rounded-xl text-white font-semibold hover:bg-gray-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver
                </a>
            </div>

        </div>
    </div>
</x-guest-layout>

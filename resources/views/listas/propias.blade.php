<x-guest-layout>
    <x-slot name="navbar">
        <div class="flex items-center justify-between w-full">
            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 bg-white/10 text-white font-semibold rounded-2xl backdrop-blur-xl hover:bg-white/20 transition flex items-center space-x-2">
                🏠 <span>Inicio</span>
            </a>

            <a href="{{ route('listas.create') }}"
                class="px-4 py-2 bg-purple-600 text-white font-semibold rounded-2xl hover:bg-purple-700 transition flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Nueva Lista</span>
            </a>
        </div>
    </x-slot>

    <!-- Contenedor principal -->
    <div class="min-h-screen flex flex-col items-start w-full sm:px-6 lg:px-8">
        <!-- Título centrado horizontalmente -->
        <h1 class="text-3xl font-bold text-white mb-6 w-full text-center">Mis Listas</h1>

        @if ($listas->isNotEmpty())
            <!-- Grid de listas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 w-full">
                @foreach ($listas as $lista)
                    <div
                        class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl overflow-hidden transform transition hover:scale-105 hover:shadow-2xl">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-white mb-2 tracking-wide">{{ $lista->name }}</h3>
                            <p class="text-gray-300 mb-4 h-12 overflow-hidden">{{ $lista->description }}</p>

                            <div class="border-t border-white/20 pt-4 flex justify-between items-center">
                                <span class="text-sm text-gray-300">{{ $lista->productos_count ?? 0 }} productos</span>
                                <a href="{{ route('listas.show', $lista) }}"
                                    class="inline-flex items-center px-3 py-1 bg-purple-600 rounded-xl text-xs font-semibold text-white hover:bg-purple-700 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd"
                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Ver
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="col-span-full text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 dark:text-gray-600 mb-4"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-xl text-gray-300 dark:text-gray-400 mb-4">No tienes listas creadas</p>
                <a href="{{ route('listas.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-semibold rounded-2xl hover:bg-purple-700 transition">
                    Crear mi primera lista
                </a>
            </div>
        @endif
    </div>
</x-guest-layout>

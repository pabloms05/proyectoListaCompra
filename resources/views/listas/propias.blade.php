<x-guest-layout>
    <x-slot name="navbar">
        <div class="flex flex-wrap items-center justify-between w-full gap-2">
            <a href="{{ route('dashboard') }}"
                class="px-3 sm:px-4 py-1.5 sm:py-2 bg-white/10 text-white font-semibold rounded-2xl backdrop-blur-xl hover:bg-white/20 transition flex items-center space-x-2 text-sm">
                🏠 <span>Inicio</span>
            </a>

            <a href="{{ route('listas.create') }}"
                class="px-3 sm:px-4 py-1.5 sm:py-2 bg-purple-600 text-white font-semibold rounded-2xl hover:bg-purple-700 transition flex items-center space-x-2 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Nueva Lista</span>
            </a>
        </div>
    </x-slot>

    <!-- Contenedor principal -->
    <div class="min-h-screen flex flex-col items-start w-full px-3 sm:px-6 lg:px-8 py-4">
        <!-- Título centrado horizontalmente -->
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white mb-3 sm:mb-6 w-full text-center">Mis Listas</h1>

        @if ($listas->isNotEmpty())
            <!-- Grid de listas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6 w-full">
                @foreach ($listas as $lista)
                    <div
                        class="relative bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl overflow-hidden transform transition hover:scale-105 hover:shadow-2xl cursor-pointer">
                        <!-- Enlace que cubre toda la tarjeta en móvil -->
                        <a href="{{ route('listas.show', $lista) }}" class="absolute inset-0 block lg:hidden" aria-label="Ver lista {{ $lista->name }}"></a>
                        <div class="p-3 sm:p-5">
                            <h3 class="text-base sm:text-lg lg:text-xl font-semibold text-white mb-2 tracking-wide truncate">{{ $lista->name }}</h3>
                            <p class="text-gray-300 text-xs sm:text-sm mb-3 h-8 sm:h-10 overflow-hidden line-clamp-2">{{ $lista->description }}</p>

                            <div class="border-t border-white/20 pt-3 sm:pt-4 space-y-2">
                                <div class="flex justify-between items-center flex-wrap gap-2">
                                    <span class="text-xs sm:text-sm text-gray-300">{{ $lista->productos_count ?? 0 }} productos</span>
                                    <!-- Botón visible solo en escritorio -->
                                    <a href="{{ route('listas.show', $lista) }}"
                                        class="hidden lg:inline-flex items-center rounded-xl text-xs font-bold text-gray-900 transition"
                                        style="background: linear-gradient(90deg, #ffde59, #ffb84d); color: #1f1f1f; padding: calc(0.25rem + 3px) calc(0.5rem + 10px);">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 mr-1" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                        Ver
                                    </a>
                                </div>
                                @if($lista->total > 0)
                                    <div class="flex items-center gap-2 text-white text-sm sm:text-base font-semibold bg-yellow-600/30 px-3 py-1 rounded-lg">
                                        <span class="text-xl">💰</span>
                                        <span>{{ number_format($lista->total, 2) }}€</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="col-span-full text-center py-8 sm:py-12 w-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 sm:h-16 sm:w-16 mx-auto text-gray-400 dark:text-gray-600 mb-3 sm:mb-4"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-lg sm:text-xl text-gray-300 dark:text-gray-400 mb-3 sm:mb-4 px-4">No tienes listas creadas</p>
                <a href="{{ route('listas.create') }}"
                    class="inline-flex items-center px-3 sm:px-4 py-2 bg-purple-600 text-white font-semibold rounded-2xl hover:bg-purple-700 transition text-sm">
                    Crear mi primera lista
                </a>
            </div>
        @endif
    </div>
</x-guest-layout>

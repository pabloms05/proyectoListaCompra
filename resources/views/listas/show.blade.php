<x-guest-layout>
    <style>
        @media (min-width: 1024px) {
            body {
                overflow: hidden;
            }
        }
    </style>
    <x-slot name="navbar">
        <div class="flex flex-wrap items-center gap-2 w-full">
            <!-- Botón Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 bg-white/10 text-white font-semibold rounded-2xl backdrop-blur-xl hover:bg-white/20 transition-colors flex items-center space-x-2">
                🏠 <span>Inicio</span>
            </a>

            <!-- Botón Mis Listas -->
            <a href="{{ request()->get('from') === 'compartidas' ? route('listas-compartidas') : route('listas.propias') }}"
                class="px-4 py-2 bg-white/10 text-white font-semibold rounded-2xl backdrop-blur-xl hover:bg-white/20 transition-colors flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>{{ request()->get('from') === 'compartidas' ? 'Listas Compartidas' : 'Mis Listas' }}</span>
            </a>
        </div>
    </x-slot>

    <!-- Contenedor principal -->
    <div class="min-h-screen w-full px-3 sm:px-4 lg:px-8 py-4 sm:py-6">

        <!-- Tarjeta de lista -->
        <div class="w-full bg-gray-900/70 rounded-xl shadow-lg border border-gray-700 p-3 sm:p-4 lg:p-6">

            <!-- Encabezado de la lista -->
            <div class="flex flex-col gap-3 mb-4">
                <div>
                    <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-white mb-1">{{ $lista->name }}</h1>
                    <p class="text-gray-300 text-xs sm:text-sm">{{ $lista->description }}</p>

                    @if (!$isOwner)
                        <div class="mt-2">
                            @if ($userRole === 'editor')
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    📝 Editor - Puedes modificar
                                </span>
                            @elseif($userRole === 'lector')
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    👁️ Lector - Solo lectura
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="flex flex-wrap gap-1.5 sm:gap-2 w-full justify-start">
                    @if ($userRole === 'owner' || $userRole === 'editor')
                        <a href="{{ route('listas.edit', $lista) }}"
                            class="inline-flex items-center justify-center w-24 sm:w-28 px-2 sm:px-3 py-1 sm:py-1.5 bg-yellow-500 hover:bg-yellow-600 rounded-md font-semibold text-white text-xs transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Editar
                        </a>
                    @endif

                    @if ($isOwner)
                        <form action="{{ route('listas.destroy', $lista) }}" method="POST" class="inline" id="form-eliminar-lista">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                class="inline-flex items-center justify-center w-24 sm:w-28 px-2 sm:px-3 py-1 sm:py-1.5 bg-red-600 hover:bg-red-700 rounded-md font-semibold text-white text-xs transition"
                                onclick="confirmarEliminarLista()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Eliminar
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Contenido principal: grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4">

                <!-- Productos por categoría -->
                <div class="lg:col-span-2 max-h-[60vh] sm:max-h-[65vh] overflow-y-auto space-y-3 pr-1 sm:pr-2">
                    @forelse($productosPorCategoria as $categoriaNombre => $productos)
                        <div class="bg-gray-800/70 rounded-lg shadow-inner p-2 sm:p-3 border border-gray-700">
                            <h3
                                class="text-xs sm:text-sm lg:text-base font-semibold text-white mb-2 border-b border-indigo-500 pb-1 flex items-center justify-between gap-1.5">
                                <span>📂 {{ $categoriaNombre }}</span>
                                <span class="text-xs font-normal text-yellow-300">
                                    💰 {{ number_format($productos->sum(function($p) { return ($p->pivot->precio_unitario ?? 0) * ($p->pivot->cantidad ?? 1); }), 2) }}€
                                </span>
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2">
                                @foreach ($productos as $producto)
                                    @if ($userRole === 'owner' || $userRole === 'editor')
                                        <form action="{{ route('listas.alternarComprado', $lista) }}" method="POST" id="form-producto-{{ $producto->id_producto }}">
                                            @csrf
                                            <input type="hidden" name="producto_id" value="{{ $producto->id_producto }}">
                                            <div
                                                class="flex flex-row items-center gap-2 p-1.5 sm:p-2 rounded-md transition cursor-pointer {{ $producto->pivot->comprado ? 'bg-green-600 hover:bg-green-500' : 'bg-gray-700 hover:bg-gray-600' }}"
                                                onclick="document.getElementById('form-producto-{{ $producto->id_producto }}').submit()">
                                                <!-- Información -->
                                                <div class="flex-1 flex flex-col justify-between min-w-0">
                                                    <h4
                                                        class="text-white text-xs sm:text-sm font-semibold truncate {{ $producto->pivot->comprado ? 'line-through opacity-80' : '' }}">
                                                        {{ $producto->name }}
                                                    </h4>
                                                    <div class="flex items-center gap-2 sm:gap-3 mt-1 flex-wrap text-xs">
                                                        <span
                                                            class="{{ $producto->pivot->comprado ? 'line-through opacity-80 text-green-100' : 'text-gray-300' }}">
                                                            Cant: <span
                                                                class="font-semibold text-indigo-200">{{ $producto->pivot->cantidad ?? 1 }}</span>
                                                            @if ($producto->unidad_medida)
                                                                {{ $producto->unidad_medida }}
                                                            @endif
                                                        </span>
                                                        <span class="text-white font-bold text-sm bg-yellow-600/30 px-2 py-0.5 rounded">
                                                            {{ number_format(($producto->pivot->precio_unitario ?? 0) * ($producto->pivot->cantidad ?? 1), 2) }}€
                                                        </span>
                                                        <span
                                                            class="px-1.5 sm:px-2 py-0.5 rounded-full text-xs {{ $producto->pivot->comprado ? 'text-green-900 bg-green-200' : 'text-gray-200 bg-gray-700' }}">
                                                            {{ $producto->pivot->comprado ? '✓' : '⏳' }}
                                                        </span>
                                                    </div>
                                                    @if ($producto->pivot->notas)
                                                        <p
                                                            class="text-gray-100 text-xs mt-0.5 truncate {{ $producto->pivot->comprado ? 'opacity-80' : '' }}">
                                                            📝 {{ $producto->pivot->notas }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </form>
                                    @else
                                        <div
                                            class="flex flex-row items-center gap-2 p-1.5 sm:p-2 rounded-md transition {{ $producto->pivot->comprado ? 'bg-green-600' : 'bg-gray-700' }}">
                                            <!-- Información -->
                                            <div class="flex-1 flex flex-col justify-between min-w-0">
                                                <h4
                                                    class="text-white text-xs sm:text-sm font-semibold truncate {{ $producto->pivot->comprado ? 'line-through opacity-80' : '' }}">
                                                    {{ $producto->name }}
                                                </h4>
                                                <div class="flex items-center gap-2 sm:gap-3 mt-1 flex-wrap text-xs">
                                                    <span
                                                        class="{{ $producto->pivot->comprado ? 'line-through opacity-80 text-green-100' : 'text-gray-300' }}">
                                                        Cant: <span
                                                            class="font-semibold text-indigo-200">{{ $producto->pivot->cantidad ?? 1 }}</span>
                                                        @if ($producto->unidad_medida)
                                                            {{ $producto->unidad_medida }}
                                                        @endif
                                                    </span>
                                                    <span class="text-white font-bold text-sm bg-yellow-600/30 px-2 py-0.5 rounded">
                                                        {{ number_format(($producto->pivot->precio_unitario ?? 0) * ($producto->pivot->cantidad ?? 1), 2) }}€
                                                    </span>
                                                    <span
                                                        class="px-1.5 sm:px-2 py-0.5 rounded-full text-xs {{ $producto->pivot->comprado ? 'text-green-900 bg-green-200' : 'text-gray-200 bg-gray-700' }}">
                                                        {{ $producto->pivot->comprado ? '✓' : '⏳' }}
                                                    </span>
                                                </div>
                                                @if ($producto->pivot->notas)
                                                    <p
                                                        class="text-gray-100 text-xs mt-0.5 truncate {{ $producto->pivot->comprado ? 'opacity-80' : '' }}">
                                                        📝 {{ $producto->pivot->notas }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-full bg-gray-800/70 rounded-lg shadow p-6 sm:p-8 text-center border border-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 sm:h-20 sm:w-20 mx-auto text-gray-400 mb-3"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-gray-300 text-base sm:text-lg mb-3 px-4">Esta lista está vacía</p>
                            @if ($isOwner)
                                <a href="{{ route('listas.edit', $lista) }}"
                                    class="inline-flex items-center px-3 sm:px-4 py-2 bg-indigo-600 rounded-md text-white font-semibold text-sm hover:bg-indigo-700 transition">
                                    Añadir productos
                                </a>
                            @endif
                        </div>
                    @endforelse
                </div>

                <!-- Panel lateral -->
                <div class="lg:col-span-1 flex flex-col gap-3 overflow-y-auto">
                    <!-- Panel de Total -->
                    <div class="bg-gradient-to-br from-yellow-600 to-orange-600 rounded-lg p-3 sm:p-4 border border-yellow-500 shadow-lg">
                        <h3 class="text-white text-sm font-semibold mb-3 flex items-center gap-2">
                            <span class="text-2xl">💰</span> Total de la Lista
                        </h3>
                        <div class="space-y-2 text-white">
                            <div class="flex justify-between items-center text-sm">
                                <span class="opacity-90">Total:</span>
                                <span class="text-xl font-bold">{{ number_format($lista->total, 2) }}€</span>
                            </div>
                            @if($lista->total_comprado > 0 || $lista->total_pendiente > 0)
                                <div class="border-t border-white/30 pt-2 space-y-1 text-xs">
                                    <div class="flex justify-between">
                                        <span class="opacity-80">✓ Comprado:</span>
                                        <span class="font-semibold">{{ number_format($lista->total_comprado, 2) }}€</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="opacity-80">⏳ Pendiente:</span>
                                        <span class="font-semibold">{{ number_format($lista->total_pendiente, 2) }}€</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($isOwner)
                        <div class="bg-gray-800/70 rounded-lg p-2 sm:p-3 border border-gray-700 shadow">
                            <h3 class="text-white text-xs sm:text-sm lg:text-base font-semibold mb-2">Gestión de Lista</h3>
                            <button type="button" onclick="abrirModalCompartir({{ $lista->id_lista }})"
                                class="w-full py-1.5 sm:py-2 bg-green-600 hover:bg-green-700 rounded-md text-white font-semibold transition text-xs sm:text-sm">
                                Compartir Lista
                            </button>

                            <h4 class="text-gray-200 font-semibold mt-3 sm:mt-4 mb-2 text-xs sm:text-sm">Categorías en la Lista</h4>
                            <div class="space-y-1">
                                @forelse($productosPorCategoria as $categoriaNombre => $productos)
                                    <div
                                        class="flex justify-between items-center py-1 px-2 bg-gray-700 rounded-md text-xs">
                                        <span class="text-gray-200 truncate flex-1">{{ $categoriaNombre }}</span>
                                        <span class="text-gray-400 ml-2 flex-shrink-0">{{ $productos->count() }}</span>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-xs text-center">No hay categorías</p>
                                @endforelse
                            </div>
                        </div>

                        @include('components.seccion-usuarios-compartidos')
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('components.modal-compartir-lista')

    @vite(['resources/js/app.js'])
    <script src="{{ asset('js/compartir-lista.js') }}?v={{ time() }}" defer></script>
    <script>
        function confirmarEliminarLista() {
            Swal.fire({
                title: '¿Eliminar lista?',
                text: "Esta acción no se puede deshacer. Se eliminará la lista y todos sus productos.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-eliminar-lista').submit();
                }
            });
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            @if ($isOwner)
                cargarUsuariosCompartidos({{ $lista->id_lista }});
            @endif
        });
    </script>
</x-guest-layout>

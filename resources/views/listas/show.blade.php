<x-guest-layout>
    <style>
        body {
            overflow: hidden;
        }
    </style>
    <x-slot name="navbar">
        <div class="flex items-center space-x-4">
            <!-- Botón Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition flex items-center space-x-2">
                🏠 <span>Inicio</span>
            </a>

            <!-- Botón Mis Listas -->
            <a href="{{ route('listas.propias') }}"
                class="px-4 py-2 bg-gray-700 text-white font-semibold rounded-lg shadow hover:bg-gray-800 transition flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Mis Listas</span>
            </a>
        </div>
    </x-slot>

    <!-- Contenedor principal -->
    <div class="min-h-screen w-full px-6 lg:px-12 py-8 flex flex-col items-start space-y-6">

        <!-- Tarjeta de lista -->
        <div class="w-full bg-gray-900/70 rounded-xl shadow-lg border border-gray-700 overflow-hidden p-6">

            <!-- Encabezado de la lista -->
            <div class="flex flex-col lg:flex-row justify-between items-start mb-6 gap-3">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-1">{{ $lista->name }}</h1>
                    <p class="text-gray-300 text-sm">{{ $lista->description }}</p>

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

                <div class="flex flex-wrap gap-2">
                    @if ($userRole === 'owner' || $userRole === 'editor')
                        <a href="{{ route('listas.edit', $lista) }}"
                            class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 rounded-md font-semibold text-white text-xs transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Editar
                        </a>
                    @endif

                    @if ($isOwner)
                        <form action="{{ route('listas.destroy', $lista) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 rounded-md font-semibold text-white text-xs transition"
                                onclick="return confirm('¿Estás seguro de que quieres eliminar esta lista?')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Eliminar
                            </button>
                        </form>
                    @endif

                    <a href="{{ $isSharedWithMe ? route('listas-compartidas') : route('listas.propias') }}"
                        class="inline-flex items-center px-3 py-1.5 bg-gray-600 hover:bg-gray-700 rounded-md font-semibold text-white text-xs transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver
                    </a>
                </div>
            </div>

            <!-- Contenido principal: grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Productos por categoría -->
                <div class="lg:col-span-2 max-h-[70vh] overflow-y-auto space-y-4 pr-2">
                    @forelse($productosPorCategoria as $categoriaNombre => $productos)
                        <div class="bg-gray-800/70 rounded-lg shadow-inner p-4 border border-gray-700">
                            <h3
                                class="text-lg font-semibold text-white mb-3 border-b border-indigo-500 pb-1 flex items-center gap-2">
                                📂 {{ $categoriaNombre }}
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach ($productos as $producto)
                                    <div
                                        class="flex flex-col p-2 rounded-md bg-gray-700 hover:bg-gray-600 transition">

                                        <!-- Botón de comprado -->
                                        @if ($userRole === 'owner' || $userRole === 'editor')
                                            <form action="{{ route('listas.alternarComprado', $lista) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="producto_id"
                                                    value="{{ $producto->id_producto }}">
                                                <button type="submit"
                                                    class="w-8 h-8 flex items-center justify-center rounded-full transition-colors {{ $producto->pivot->comprado ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-500 hover:bg-gray-400' }}"
                                                    title="{{ $producto->pivot->comprado ? 'Marcar como pendiente' : 'Marcar como comprado' }}">
                                                    @if ($producto->pivot->comprado)
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-4 w-4 text-white" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    @else
                                                        <span class="text-gray-300 text-sm">○</span>
                                                    @endif
                                                </button>
                                            </form>
                                        @else
                                            <div
                                                class="w-8 h-8 flex items-center justify-center rounded-full {{ $producto->pivot->comprado ? 'bg-green-500' : 'bg-gray-500' }}">
                                                @if ($producto->pivot->comprado)
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <span class="text-gray-300 text-sm">○</span>
                                                @endif
                                            </div>
                                        @endif

                                        <!-- Imagen -->
                                        @if ($producto->image_path)
                                            <img src="{{ Storage::url($producto->image_path) }}"
                                                alt="{{ $producto->name }}"
                                                class="w-16 h-16 object-cover rounded-md {{ $producto->pivot->comprado ? 'opacity-50' : '' }}">
                                        @else
                                            <div
                                                class="w-16 h-16 bg-gray-600 rounded-md flex items-center justify-center {{ $producto->pivot->comprado ? 'opacity-50' : '' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 7l-8-4-8 4m16 0l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                            </div>
                                        @endif

                                        <!-- Información -->
                                        <div class="flex-1 flex flex-col justify-between">
                                            <h4
                                                class="text-white text-sm font-semibold {{ $producto->pivot->comprado ? 'line-through opacity-60' : '' }}">
                                                {{ $producto->name }}
                                            </h4>
                                            <div class="flex items-center gap-2 mt-1 flex-wrap text-xs">
                                                <span
                                                    class="{{ $producto->pivot->comprado ? 'line-through opacity-60 text-gray-400' : 'text-gray-300' }}">
                                                    Cant: <span
                                                        class="font-semibold text-indigo-400">{{ $producto->pivot->cantidad ?? 1 }}</span>
                                                    @if ($producto->unidad_medida)
                                                        {{ $producto->unidad_medida }}
                                                    @endif
                                                </span>
                                                <span
                                                    class="px-2 py-0.5 rounded-full {{ $producto->pivot->comprado ? 'text-green-800 bg-green-100' : 'text-gray-200 bg-gray-700' }}">
                                                    {{ $producto->pivot->comprado ? '✓ Comprado' : '⏳ Pendiente' }}
                                                </span>
                                            </div>
                                            @if ($producto->pivot->notas)
                                                <p
                                                    class="text-gray-400 text-xs mt-0.5 {{ $producto->pivot->comprado ? 'line-through opacity-60' : '' }}">
                                                    📝 {{ $producto->pivot->notas }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-full bg-gray-800/70 rounded-lg shadow p-8 text-center border border-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto text-gray-400 mb-3"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-gray-300 text-lg mb-3">Esta lista está vacía</p>
                            @if ($isOwner)
                                <a href="{{ route('listas.edit', $lista) }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-md text-white font-semibold text-sm hover:bg-indigo-700 transition">
                                    Añadir productos
                                </a>
                            @endif
                        </div>
                    @endforelse
                </div>

                <!-- Panel lateral -->
                <div class="lg:col-span-1 flex flex-col gap-4">
                    @if ($isOwner)
                        <div class="bg-gray-800/70 rounded-lg p-4 border border-gray-700 shadow">
                            <h3 class="text-white font-semibold mb-3">Gestión de Lista</h3>
                            <button type="button" onclick="abrirModalCompartir({{ $lista->id_lista }})"
                                class="w-full py-2 bg-green-600 hover:bg-green-700 rounded-md text-white font-semibold transition">
                                Compartir Lista
                            </button>

                            <h4 class="text-gray-200 font-semibold mt-4 mb-2 text-sm">Categorías en la Lista</h4>
                            <div class="space-y-1">
                                @forelse($productosPorCategoria as $categoriaNombre => $productos)
                                    <div
                                        class="flex justify-between items-center py-1 px-2 bg-gray-700 rounded-md text-xs">
                                        <span class="text-gray-200">{{ $categoriaNombre }}</span>
                                        <span class="text-gray-400">{{ $productos->count() }} productos</span>
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
    <script src="{{ asset('js/compartir-lista.js') }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($isOwner)
                cargarUsuariosCompartidos({{ $lista->id_lista }});
            @endif
        });
    </script>
</x-guest-layout>

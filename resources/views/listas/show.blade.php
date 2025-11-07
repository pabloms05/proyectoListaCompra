<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $lista->name }}</h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $lista->description }}</p>
                            
                            {{-- Indicador de rol --}}
                            @if(!$isOwner)
                                <div class="mt-2">
                                    @if($userRole === 'editor')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            📝 Editor - Puedes modificar
                                        </span>
                                    @elseif($userRole === 'lector')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            👁️ Lector - Solo lectura
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="flex space-x-3">
                            
                            {{-- Botón Editar: para owner y editor --}}
                            @if($userRole === 'owner' || $userRole === 'editor')
                                <a href="{{ route('listas.edit', $lista) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Editar
                                </a>
                            @endif

                            {{-- Botón Eliminar: SOLO para owner --}}
                            @if($isOwner)
                                <form action="{{ route('listas.destroy', $lista) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700" onclick="return confirm('¿Estás seguro de que quieres eliminar esta lista?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Eliminar
                                    </button>
                                </form>
                            @endif
                            
                            {{-- Botón Volver: cambia según si es compartida o propia --}}
                            <a href="{{ $isSharedWithMe ? route('listas-compartidas') : route('listas.propias') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Volver
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <div class="lg:col-span-2">
                            @forelse($productosPorCategoria as $categoriaNombre => $productos)
                                <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-lg mb-6">
                                    <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white border-b-2 border-indigo-500 pb-2">
                                        📂 {{ $categoriaNombre }}
                                    </h3>

                                    <div class="space-y-3">
                                        @foreach($productos as $producto)
                                            <div class="flex items-center bg-gray-50 dark:bg-gray-800 p-4 rounded-lg hover:shadow-md transition-shadow">
                                                
                                                <!-- Botón de marcar como comprado (solo para owner y editor) -->
                                                @if($userRole === 'owner' || $userRole === 'editor')
                                                    <form action="{{ route('listas.alternarComprado', $lista) }}" method="POST" class="mr-3">
                                                        @csrf
                                                        <input type="hidden" name="producto_id" value="{{ $producto->id_producto }}">
                                                        <button type="submit" 
                                                                class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center transition-colors {{ $producto->pivot->comprado ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500' }}"
                                                                title="{{ $producto->pivot->comprado ? 'Marcar como pendiente' : 'Marcar como comprado' }}">
                                                            @if($producto->pivot->comprado)
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                                </svg>
                                                            @else
                                                                <span class="text-gray-400 text-xs">○</span>
                                                            @endif
                                                        </button>
                                                    </form>
                                                @else
                                                    {{-- Solo mostrar el estado para lectores --}}
                                                    <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center mr-3 {{ $producto->pivot->comprado ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}"
                                                         title="Solo lectura">
                                                        @if($producto->pivot->comprado)
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                        @else
                                                            <span class="text-gray-400 text-xs">○</span>
                                                        @endif
                                                    </div>
                                                @endif

                                                @if($producto->image_path)
                                                    <img src="{{ Storage::url($producto->image_path) }}"
                                                        alt="{{ $producto->name }}"
                                                        class="w-20 h-20 object-cover rounded-md mr-4 {{ $producto->pivot->comprado ? 'opacity-50' : '' }}">
                                                @else
                                                    <div class="w-20 h-20 bg-gray-300 dark:bg-gray-600 rounded-md mr-4 flex items-center justify-center {{ $producto->pivot->comprado ? 'opacity-50' : '' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                        </svg>
                                                    </div>
                                                @endif

                                                <div class="flex-1">
                                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white {{ $producto->pivot->comprado ? 'line-through opacity-60' : '' }}">
                                                        {{ $producto->name }}
                                                    </h4>
                                                    <div class="flex items-center space-x-4 mt-1">
                                                        <p class="text-sm text-gray-600 dark:text-gray-300 {{ $producto->pivot->comprado ? 'line-through opacity-60' : '' }}">
                                                            <span class="font-medium">Cantidad:</span> 
                                                            <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $producto->pivot->cantidad ?? 1 }}</span>
                                                            @if($producto->unidad_medida)
                                                                {{ $producto->unidad_medida }}
                                                            @endif
                                                        </p>
                                                        @if($producto->pivot->comprado)
                                                            <span class="inline-flex items-center px-3 py-1 text-xs font-bold text-green-800 bg-green-100 rounded-full">
                                                                ✓ Comprado
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-3 py-1 text-xs font-bold text-gray-600 bg-gray-200 rounded-full">
                                                                ⏳ Pendiente
                                                            </span>
                                                        @endif
                                                    </div>
                                                    @if($producto->pivot->notas)
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 {{ $producto->pivot->comprado ? 'line-through opacity-60' : '' }}">
                                                            📝 {{ $producto->pivot->notas }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full bg-white dark:bg-gray-700 p-12 rounded-lg shadow text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-xl text-gray-500 dark:text-gray-400 mb-4">Esta lista está vacía</p>
                                    @if($isOwner)
                                        <a href="{{ route('listas.edit', $lista) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-md font-semibold text-xs text-white uppercase hover:bg-indigo-700">
                                            Añadir productos
                                        </a>
                                    @endif
                                </div>
                            @endforelse
                        </div>

                        <div class="lg:col-span-1 space-y-4">
                            
                            @if($isOwner)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Gestión de Lista</h3>
                                    
                                    <div class="mb-4">
                                        <button 
                                            type="button" 
                                            onclick="abrirModalCompartir({{ $lista->id_lista }})" 
                                            class="inline-flex items-center px-4 py-2 bg-green-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 w-full justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                            </svg>
                                            Compartir Lista
                                        </button>
                                    </div>
                                    
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Categorías en la Lista</h4>
                                    <div class="space-y-2">
                                        @forelse($productosPorCategoria as $categoriaNombre => $productos)
                                            <div class="flex items-center justify-between py-2 px-3 bg-white dark:bg-gray-600 rounded-lg">
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $categoriaNombre }}</span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $productos->count() }} productos</span>
                                            </div>
                                        @empty
                                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center">No hay categorías</p>
                                        @endforelse
                                    </div>
                                </div>
                            @endif
                            
                            {{-- Sección de usuarios compartidos (solo para propietario) --}}
                            @if($isOwner)
                                @include('components.seccion-usuarios-compartidos')
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Incluir el modal de compartir --}}
    @include('components.modal-compartir-lista')
    
    {{-- Scripts --}}
    @vite(['resources/js/app.js'])
    <script src="{{ asset('js/compartir-lista.js') }}" defer></script>
    <script>
        // Cargar usuarios compartidos al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            @if($isOwner)
            cargarUsuariosCompartidos({{ $lista->id_lista }});
            @endif
        });
    </script>
</x-app-layout>
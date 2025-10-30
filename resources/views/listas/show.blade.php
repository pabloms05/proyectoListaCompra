<x-app-layout>
    <div class="py-12" x-data="{
        // Solo mantenemos el modal de Compartir, ya que es una acción de la lista, no de edición de contenido.
        showCompartirModal: false
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $lista->name }}</h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $lista->description }}</p>
                        </div>

                        <div class="flex space-x-3">
                            
                            @if($isOwner)
                                <a href="{{ route('listas.edit', $lista) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Editar
                                </a>

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
                            
                            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Volver
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <div class="lg:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @forelse($productosPorCategoria as $categoriaNombre => $productos)
                                    <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                                        <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">
                                            {{ $categoriaNombre }}
                                        </h3>

                                        <div class="space-y-4">
                                            @foreach($productos as $producto)
                                                <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-md">
                                                    @if($producto->image_path)
                                                        <img src="{{ Storage::url($producto->image_path) }}"
                                                            alt="{{ $producto->name }}"
                                                            class="w-full h-32 object-cover rounded-md mb-2">
                                                    @endif

                                                    <div class="flex justify-between items-start">
                                                        <div>
                                                            <h4 class="font-medium text-gray-900 dark:text-white">
                                                                {{ $producto->name }}
                                                            </h4>
                                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                                Cantidad: {{ $producto->pivot->cantidad ?? 1 }}
                                                            </p>
                                                            @if($producto->pivot->comprado)
                                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                                                    ✓ Comprado
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full">
                                        <p class="text-gray-500 dark:text-gray-400 text-center">
                                            Esta lista está vacía. Usa el botón **Editar** para añadir categorías y productos.
                                        </p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="lg:col-span-1 space-y-4">
                            
                            @if($isOwner)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Gestión de Lista</h3>
                                    
                                    <div class="mb-4">
                                        <button type="button" @click="showCompartirModal = true" class="inline-flex items-center px-4 py-2 bg-green-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 w-full justify-center">
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
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div x-show="showCompartirModal" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showCompartirModal" @click="showCompartirModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-white">Compartir Lista</h3>
                        <form action="{{ route('listas.share', $lista) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email del usuario</label>
                                <input type="email"
                                        name="email"
                                        id="email"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-sm p-2"
                                        required>
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rol</label>
                                <select name="role"
                                        id="role"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-sm p-2"
                                        required>
                                    <option value="viewer">Solo lectura</option>
                                    <option value="editor">Editor</option>
                                </select>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="button"
                                        @click="showCompartirModal = false"
                                        class="inline-flex items-center px-3 py-2 border dark:border-gray-600 rounded-md text-sm text-gray-700 dark:text-gray-300">
                                    Cancelar
                                </button>
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                                    Compartir
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
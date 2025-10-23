<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Cabecera -->
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $lista->name }}</h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $lista->description }}</p>
                        </div>

                        <div class="flex space-x-3">
                            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Volver
                            </a>

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
                        </div>
                    </div>

                    <!-- Barra de herramientas simple -->
                    <div class="mb-6 flex items-center justify-between">
                        <div class="flex space-x-2">
                            <a href="{{ route('listas.index') }}" class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded">Todas</a>
                            <a href="{{ route('listas.propias') }}" class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded">Propias</a>
                        </div>
                        <div>
                            @if($isOwner)
                                <button @click="showCompartirModal = true" class="px-3 py-1 bg-green-600 text-white rounded">Compartir</button>
                            @endif
                        </div>
                    </div>

                    <!-- Contenido Principal -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Panel de Productos (tarjetas por categoría) -->
                        <div class="lg:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @forelse($categorias as $categoria)
                                    <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                                        <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">
                                            {{ $categoria->name }}
                                        </h3>

                                        <div class="space-y-4">
                                            @foreach($categoria->productos as $producto)
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
                                                                Cantidad: {{ $producto->cantidad }}
                                                            </p>
                                                        </div>

                                                        <div class="flex space-x-2">
                                                            <!-- botón: uso data-attribute en vez de inline onclick con Blade -->
                                                            <button class="text-blue-600 hover:text-blue-800 btn-editar-producto" data-producto-id="{{ $producto->id }}">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                            </button>

                                                            <form action="{{ route('productos.destroy', ['lista' => $lista, 'producto' => $producto]) }}"
                                                                  method="POST"
                                                                  class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                        class="text-red-600 hover:text-red-800"
                                                                        onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?')">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full">
                                        <p class="text-gray-500 dark:text-gray-400 text-center">
                                            No hay categorías en esta lista.
                                        </p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Panel lateral -->
                        <div class="lg:col-span-1 space-y-4">
                            <!-- Formulario para añadir productos (inline simple) -->
                            <div class="bg-white dark:bg-gray-700 rounded-lg p-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Añadir producto</h3>
                                <form action="{{ route('productos.store', $lista) }}" method="POST" class="space-y-2" enctype="multipart/form-data">
                                    @csrf
                                    <div>
                                        <label class="block text-sm text-gray-700 dark:text-gray-300">Nombre</label>
                                        <input name="name" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-sm p-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700 dark:text-gray-300">Cantidad</label>
                                        <input type="number" name="cantidad" value="1" min="1" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-sm p-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700 dark:text-gray-300">Categoría</label>
                                        <select name="categoria_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-sm p-2">
                                            <option value="">Selecciona</option>
                                            @foreach($categorias as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded">Añadir</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Gestión de categorías -->
                            @if($isOwner)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Gestión</h3>
                                        <div class="flex space-x-2">
                                            <button @click="showCategoriaModal = true" class="text-yellow-600 hover:text-yellow-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        @if($categorias->isEmpty())
                                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center">No hay categorías</p>
                                        @else
                                            @foreach($categorias as $categoria)
                                                <div class="flex items-center justify-between py-2 px-3 bg-white dark:bg-gray-600 rounded-lg">
                                                    <div class="flex items-center">
                                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $categoria->name }}</span>
                                                    </div>
                                                    <div class="flex space-x-2">
                                                        <form action="{{ route('categorias.destroy', ['lista' => $lista, 'categoria' => $categoria]) }}" method="POST" onsubmit="return confirm('¿Eliminar categoría?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-700">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Productos resumen -->
                        <div class="lg:col-span-2">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Productos</h2>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @forelse($lista->categorias as $categoria)
                                        @foreach($categoria->productos as $producto)
                                            <div class="bg-white dark:bg-gray-600 rounded-lg shadow p-4 flex items-center space-x-4">
                                                @if($producto->image)
                                                    <img src="{{ $producto->image }}" alt="{{ $producto->name }}" class="w-16 h-16 object-cover rounded">
                                                @else
                                                    <div class="w-16 h-16 bg-gray-200 dark:bg-gray-500 rounded flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif

                                                <div class="flex-1">
                                                    <h3 class="text-gray-900 dark:text-white font-medium">{{ $producto->name }}</h3>
                                                    <p class="text-gray-600 dark:text-gray-300 text-sm">{{ $categoria->name }}</p>
                                                    <div class="flex items-center mt-2">
                                                        <input type="number" value="{{ $producto->cantidad }}" min="1" class="w-20 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-sm">
                                                        <span class="ml-2 text-gray-600 dark:text-gray-300 text-sm">unidades</span>
                                                    </div>
                                                </div>

                                                <div class="flex space-x-2">
                                                    <button class="text-yellow-600 hover:text-yellow-700">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                        </svg>
                                                    </button>
                                                    <button class="text-red-600 hover:text-red-700">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @empty
                                        <div class="col-span-2">
                                            <p class="text-gray-500 dark:text-gray-400 text-center py-4">No hay productos aún</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón Compartir (solo para propietarios) -->
                    @if($isOwner)
                        <div class="mt-6">
                            <button type="button" @click="showCompartirModal = true" class="inline-flex items-center px-4 py-2 bg-green-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                </svg>
                                Compartir Lista
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modales (formularios inline) -->
    <div x-data="{
        showCategoriaModal: false,
        showProductoModal: false,
        showCompartirModal: false
    }">
        <!-- Modal Categoría -->
        <div x-show="showCategoriaModal" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showCategoriaModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4">Crear categoría</h3>
                        <form action="{{ route('categorias.store', $lista) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
                            </div>
                            <div class="flex justify-end">
                                <button type="button" @click="showCategoriaModal = false" class="px-3 py-2 border rounded mr-2">Cancelar</button>
                                <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded">Crear</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Producto -->
        <div x-show="showProductoModal" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showProductoModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4">Crear producto</h3>
                        <form id="form-producto-modal" action="{{ route('productos.store', $lista) }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                                <input type="number" name="cantidad" value="1" min="1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Imagen</label>
                                <input type="file" name="image" class="mt-1 block w-full text-sm">
                            </div>
                            <div class="flex justify-end">
                                <button type="button" @click="showProductoModal = false" class="px-3 py-2 border rounded mr-2">Cancelar</button>
                                <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded">Crear</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Compartir -->
        <div x-show="showCompartirModal" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showCompartirModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4">Compartir Lista</h3>
                        <form action="{{ route('listas.share', $lista) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email del usuario</label>
                                <input type="email"
                                       name="email"
                                       id="email"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2"
                                       required>
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700">Rol</label>
                                <select name="role"
                                        id="role"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2"
                                        required>
                                    <option value="viewer">Solo lectura</option>
                                    <option value="editor">Editor</option>
                                </select>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="button"
                                        @click="showCompartirModal = false"
                                        class="inline-flex items-center px-3 py-2 border rounded-md text-sm">
                                    Cancelar
                                </button>
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md text-sm">
                                    Compartir
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Delegated click para botones de editar producto (evita inline onclick con Blade)
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('button.btn-editar-producto');
            if (!btn) return;
            const id = btn.getAttribute('data-producto-id');
            if (!id) return console.error('Producto sin id');
            editarProducto(id);
        });

        function editarProducto(id) {
            // ejemplo: abrir modal de producto; si usas el mismo modal para crear/editar,
            // puedes prefijar el campo action y rellenar inputs con fetch/AJAX.
            // Por ahora mostramos modal simple
            console.log('Editar producto', id);
            // abrir modal (ejemplo)
            // document.querySelector('div[x-data]').__x.$data.showProductoModal = true; // si usas Alpine y expones
            alert('Editar producto ' + id);
        }

        function toggleProducto(id) {
            console.log('toggle producto', id);
            // implementar petición fetch/ajax para marcar completado
        }
    </script>
    @endpush
</x-app-layout>

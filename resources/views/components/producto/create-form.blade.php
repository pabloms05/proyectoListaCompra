<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="mb-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Añadir Producto</h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Añade un nuevo producto a la lista.</p>
        </div>
    
        <div class="mt-5">
            <form action="{{ route('productos.store', $lista) }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <x-input-label for="name" value="Nombre del producto" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="cantidad" value="Cantidad" />
                    <x-text-input id="cantidad" name="cantidad" type="number" min="1" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('cantidad')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="categoria_id" value="Categoría" />
                    <select id="categoria_id" name="categoria_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('categoria_id')" class="mt-2" />
                </div>

                <div class="flex justify-end">
                    <x-primary-button>Añadir Producto</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>

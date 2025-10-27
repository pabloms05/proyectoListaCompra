<x-form.section>
    <x-slot name="title">Añadir Producto</x-slot>
    <x-slot name="description">Añade un nuevo producto a la lista.</x-slot>
    
    <div class="mt-5">
        <form action="{{ route('productos.store', $lista) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
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

            <div>
                <x-input-label for="image" value="Imagen (opcional)" />
                <input type="file" id="image" name="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                <x-input-error :messages="$errors->get('image')" class="mt-2" />
            </div>

            <div class="flex justify-end">
                <x-primary-button>Añadir Producto</x-primary-button>
            </div>
        </form>
    </div>
</x-form.section>
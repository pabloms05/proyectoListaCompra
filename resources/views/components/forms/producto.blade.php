<div class="bg-white dark:bg-gray-700 rounded-lg p-4">
    <h3 class="text-lg font-medium mb-4">{{ isset($producto) ? 'Editar' : 'Nuevo' }} Producto</h3>
    
    <form action="{{ isset($producto) ? route('productos.update', ['lista' => $lista, 'producto' => $producto]) : route('productos.store', $lista) }}" 
          method="POST" 
          enctype="multipart/form-data"
          class="space-y-4">
        @csrf
        @if(isset($producto))
            @method('PUT')
        @endif

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Producto</label>
            <input type="text" 
                   name="name" 
                   id="name" 
                   value="{{ old('name', isset($producto) ? $producto->name : '') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                   required>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="categoria_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoría</label>
            <select name="categoria_id" 
                    id="categoria_id" 
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    required>
                <option value="">Selecciona una categoría</option>
                @foreach($lista->categorias as $categoria)
                    <option value="{{ $categoria->id }}" 
                            {{ old('categoria_id', isset($producto) ? $producto->categoria_id : '') == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->name }}
                    </option>
                @endforeach
            </select>
            @error('categoria_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="cantidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cantidad</label>
            <input type="number" 
                   name="cantidad" 
                   id="cantidad" 
                   value="{{ old('cantidad', isset($producto) ? $producto->cantidad : 1) }}"
                   min="1"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                   required>
            @error('cantidad')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Imagen del Producto</label>
            <input type="file" 
                   name="image" 
                   id="image" 
                   accept="image/*"
                   class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-md file:border-0
                          file:text-sm file:font-semibold
                          file:bg-indigo-50 file:text-indigo-700
                          dark:file:bg-indigo-900 dark:file:text-indigo-300
                          hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800">
            @error('image')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-3">
            @if(isset($producto))
                <button type="button" 
                        onclick="closeEditForm()"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancelar
                </button>
            @endif
            <button type="submit"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ isset($producto) ? 'Actualizar' : 'Crear' }}
            </button>
        </div>
    </form>
</div>
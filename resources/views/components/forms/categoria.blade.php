<div class="bg-white dark:bg-gray-700 rounded-lg p-4">
    <h3 class="text-lg font-medium mb-4">{{ isset($categoria) ? 'Editar' : 'Nueva' }} Categoría</h3>
    
    <form action="{{ isset($categoria) ? route('categorias.update', ['lista' => $lista, 'categoria' => $categoria]) : route('categorias.store', $lista) }}" 
          method="POST" 
          class="space-y-4">
        @csrf
        @if(isset($categoria))
            @method('PUT')
        @endif

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre de la Categoría</label>
            <input type="text" 
                   name="name" 
                   id="name" 
                   value="{{ old('name', isset($categoria) ? $categoria->name : '') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                   required>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-3">
            @if(isset($categoria))
                <button type="button" 
                        onclick="closeEditForm()"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancelar
                </button>
            @endif
            <button type="submit"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ isset($categoria) ? 'Actualizar' : 'Crear' }}
            </button>
        </div>
    </form>
</div>
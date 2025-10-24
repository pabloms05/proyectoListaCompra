<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl font-semibold mb-6">Editar Lista: {{ $lista->name }}</h2>
                    
                    <form action="{{ route('listas.update', $lista) }}" method="POST" class="max-w-4xl" x-data="{
                        // Usamos json_encode para pasar la estructura de datos anidada de PHP a JavaScript
                        categorias: {{ $lista->categorias->map(function($categoria) {
                            return [
                                'id' => $categoria->id,
                                'name' => $categoria->name,
                                'productos' => $categoria->productos->map(fn($p) => [
                                    'id' => $p->id, 
                                    'name' => $p->name, 
                                    'cantidad' => $p->cantidad
                                ])->toArray()
                            ];
                        })->toJson() }}
                    }">
                        @csrf
                        @method('PUT') <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                TITULO
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $lista->name) }}" 
                                   required
                                   class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" />
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                DESCRIPCION (Opcional)
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4"
                                      class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('description', $lista->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6 border-t pt-6 dark:border-gray-700">
                            <h3 class="text-xl font-semibold mb-4">Categorías y Productos</h3>

                            <div x-data="{ 
                                // Funciones para añadir/borrar categorías y productos
                                addCategoria() {
                                    this.categorias.push({
                                        id: 'temp_' + Date.now(), // Usar ID temporal para las nuevas
                                        name: 'Nueva Categoría',
                                        productos: [{ id: 'temp_p' + Date.now() + 'a', name: '', cantidad: 1 }] 
                                    });
                                },
                                addProducto(categoria) {
                                    categoria.productos.push({
                                        id: 'temp_p' + Date.now(), 
                                        name: '', 
                                        cantidad: 1
                                    });
                                }
                            }">
                                
                                <button type="button" @click="addCategoria()" class="px-3 py-2 bg-blue-600 text-white rounded-md mb-4 hover:bg-blue-700 transition">
                                    + Añadir Categoría
                                </button>

                                <template x-for="(categoria, indexCat) in categorias" :key="categoria.id">
                                    <div class="p-4 mb-6 border rounded-lg dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                                        
                                        <div class="flex justify-between items-center mb-3">
                                            <input type="text"
                                                :name="'categorias[' + indexCat + '][name]'"
                                                x-model="categoria.name"
                                                placeholder="Nombre de la Categoría"
                                                required
                                                class="text-lg font-medium rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-white shadow-sm w-3/4">

                                            <input type="hidden" :name="'categorias[' + indexCat + '][id]'" :value="categoria.id">

                                            <button type="button" 
                                                    @click="categorias.splice(indexCat, 1)" 
                                                    class="text-red-500 hover:text-red-700 text-sm">
                                                Borrar Cat.
                                            </button>
                                        </div>

                                        <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">PRODUCTOS Y SUS CANTIDADES:</h4>
                                        
                                        <div class="space-y-2">
                                            <template x-for="(producto, indexProd) in categoria.productos" :key="producto.id">
                                                <div class="flex items-center space-x-2 p-2 bg-white dark:bg-gray-800 rounded-md">
                                                    
                                                    <input type="text"
                                                        :name="'categorias[' + indexCat + '][productos][' + indexProd + '][name]'"
                                                        x-model="producto.name"
                                                        placeholder="Nombre del Producto"
                                                        class="w-1/2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-sm text-white">

                                                    <input type="number"
                                                        :name="'categorias[' + indexCat + '][productos][' + indexProd + '][cantidad]'"
                                                        x-model.number="producto.cantidad"
                                                        min="1"
                                                        required
                                                        class="w-1/4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-sm text-white">
                                                    
                                                    <input type="hidden" :name="'categorias[' + indexCat + '][productos][' + indexProd + '][id]'" :value="producto.id">

                                                    <button type="button" 
                                                            @click="categoria.productos.splice(indexProd, 1)" 
                                                            class="text-red-400 hover:text-red-600 text-sm">
                                                        Borrar
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                        
                                        <div class="mt-3 text-right">
                                            <button type="button" @click="addProducto(categoria)" class="px-3 py-1 text-sm bg-purple-600 text-white rounded hover:bg-purple-700 transition">
                                                + Añadir Producto
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>


                        <div class="flex justify-end space-x-4 mt-6">
                            <a href="{{ route('listas.propias') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
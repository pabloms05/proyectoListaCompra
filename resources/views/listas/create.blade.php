<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl font-semibold mb-6">Crear Nueva Lista</h2>
                    
                    <form action="{{ route('listas.store') }}" method="POST" class="max-w-4xl" x-data="{
                        categoriasMaestras: {{ (App\Models\Categoria::with('productos')->get())->toJson() }},
                        productosSeleccionados: [],
                        categoriaActual: '',
                        productosFiltrados: [],
                        productoAAnadirId: '',
                        productoAAnadirCantidad: 1,
                        
                        filtrarProductos() {
                            const cat = this.categoriasMaestras.find(c => c.id_categoria == this.categoriaActual);
                            this.productosFiltrados = cat ? cat.productos : [];
                            this.productoAAnadirId = '';
                        },
                        
                        addProducto() {
                            if (!this.productoAAnadirId) return;

                            const cat = this.categoriasMaestras.find(c => c.id_categoria == this.categoriaActual);
                            const newProd = cat.productos.find(p => p.id_producto == this.productoAAnadirId);

                            if (this.productosSeleccionados.some(p => p.id_producto === newProd.id_producto)) {
                                alert('Este producto ya ha sido añadido a la lista.');
                                return;
                            }

                            this.productosSeleccionados.push({
                                id_producto: newProd.id_producto,
                                name: newProd.name,
                                cantidad: this.productoAAnadirCantidad,
                                id_categoria: newProd.id_categoria,
                                unidad_medida: newProd.unidad_medida
                            });

                            this.productoAAnadirId = '';
                            this.productoAAnadirCantidad = 1;
                        }
                    }">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">TITULO</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        </div>
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">DESCRIPCION</label>
                            <textarea id="description" name="description" rows="4" class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('description') }}</textarea>
                        </div>
                        
                        <div class="p-4 mb-8 border rounded-lg dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                             <h3 class="text-xl font-semibold mb-4">Añadir Productos</h3>
                            <div class="flex space-x-4 items-end">
                                
                                <div class="flex-1">
                                    <label for="select-cat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoría</label>
                                    <select id="select-cat" x-model="categoriaActual" @change="filtrarProductos()" class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-white">
                                        <option value="">-- Selecciona Categoría --</option>
                                        <template x-for="cat in categoriasMaestras" :key="cat.id_categoria">
                                            <option :value="cat.id_categoria" x-text="cat.nombre"></option>
                                        </template>
                                    </select>
                                </div>

                                <div class="flex-1">
                                    <label for="select-prod" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Producto</label>
                                    <select id="select-prod" x-model="productoAAnadirId" :disabled="!categoriaActual" class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-white">
                                        <option value="">-- Selecciona Producto --</option>
                                        <template x-for="prod in productosFiltrados" :key="prod.id_producto">
                                            <option :value="prod.id_producto" x-text="prod.name"></option>
                                        </template>
                                    </select>
                                </div>
                                
                                <div class="w-1/6">
                                    <label for="cantidad-add" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cantidad</label>
                                    <input type="number" id="cantidad-add" x-model.number="productoAAnadirCantidad" min="1" class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-white">
                                </div>

                                <button type="button" @click="addProducto()" :disabled="!productoAAnadirId" class="px-3 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                                    Añadir
                                </button>
                            </div>
                        </div>


                        <div class="mb-6 border-t pt-6 dark:border-gray-700">
                            <h3 class="text-xl font-semibold mb-4">Productos en la Lista (<span x-text="productosSeleccionados.length"></span>)</h3>

                            <div class="space-y-3">
                                <template x-for="(producto, index) in productosSeleccionados" :key="producto.id_producto">
                                    <div class="flex items-center space-x-4 p-3 border rounded-md dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
                                        <span x-text="producto.name" class="font-medium flex-1 text-gray-900 dark:text-gray-100"></span>
                                        
                                        <input type="hidden" :name="'productos[' + index + '][producto_id]'" :value="producto.id_producto">

                                        <div class="w-1/5">
                                            <input type="number"
                                                :name="'productos[' + index + '][cantidad]'"
                                                x-model.number="producto.cantidad"
                                                min="1"
                                                required
                                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-sm text-white">
                                        </div>
                                        
                                        <button type="button" 
                                                @click="productosSeleccionados.splice(index, 1)" 
                                                class="text-red-500 hover:text-red-700 text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.919a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .534c1.242-1.01 2.91-1.021 4.59.034 1.258.85 3.09 1.015 4.31.024 1.13-1.006 2.47-1.006 3.6 0 1.25.845 2.918.845 4.59.027m-12 5.534c1.242-1.01 2.91-1.021 4.59.034 1.258.85 3.09 1.015 4.31.024 1.13-1.006 2.47-1.006 3.6 0 1.25.845 2.918.845 4.59.027" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                                <p x-show="productosSeleccionados.length === 0" class="text-gray-500 dark:text-gray-400 italic">No hay productos seleccionados.</p>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Crear Lista
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
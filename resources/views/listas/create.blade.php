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
                            
                            <!-- Productos existentes -->
                            <div class="mb-4">
                                <h4 class="text-md font-medium mb-2 text-gray-700 dark:text-gray-300">Desde productos existentes</h4>
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

                            <!-- Separador -->
                            <div class="relative my-4">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-gray-50 dark:bg-gray-900 text-gray-500">O</span>
                                </div>
                            </div>

                            <!-- Crear nuevo producto -->
                            <div x-data="{
                                nuevoProductoNombre: '',
                                nuevoProductoCategoria: '',
                                nuevoProductoCantidad: 1,
                                
                                addNuevoProducto() {
                                    if (!this.nuevoProductoNombre.trim() || !this.nuevoProductoCategoria) {
                                        alert('Por favor, ingresa un nombre y selecciona una categoría para el nuevo producto.');
                                        return;
                                    }
                                    
                                    const cat = categoriasMaestras.find(c => c.id_categoria == this.nuevoProductoCategoria);
                                    
                                    // Añadir el producto nuevo a la lista con un ID temporal negativo
                                    productosSeleccionados.push({
                                        id_producto: 'nuevo_' + Date.now(), // ID temporal único
                                        name: this.nuevoProductoNombre.trim(),
                                        cantidad: this.nuevoProductoCantidad,
                                        id_categoria: this.nuevoProductoCategoria,
                                        categoria_nombre: cat.nombre,
                                        es_nuevo: true // Marca para identificar productos nuevos
                                    });
                                    
                                    // Resetear campos
                                    this.nuevoProductoNombre = '';
                                    this.nuevoProductoCategoria = '';
                                    this.nuevoProductoCantidad = 1;
                                }
                            }">
                                <h4 class="text-md font-medium mb-2 text-gray-700 dark:text-gray-300">Crear nuevo producto</h4>
                                <div class="flex space-x-4 items-end">
                                    <div class="flex-1">
                                        <label for="nuevo-prod-nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre del Producto</label>
                                        <input type="text" id="nuevo-prod-nombre" x-model="nuevoProductoNombre" placeholder="Ej: Pavo" class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-white">
                                    </div>

                                    <div class="flex-1">
                                        <label for="nuevo-prod-cat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoría</label>
                                        <select id="nuevo-prod-cat" x-model="nuevoProductoCategoria" class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-white">
                                            <option value="">-- Selecciona Categoría --</option>
                                            <template x-for="cat in categoriasMaestras" :key="cat.id_categoria">
                                                <option :value="cat.id_categoria" x-text="cat.nombre"></option>
                                            </template>
                                        </select>
                                    </div>
                                    
                                    <div class="w-1/6">
                                        <label for="nuevo-prod-cantidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cantidad</label>
                                        <input type="number" id="nuevo-prod-cantidad" x-model.number="nuevoProductoCantidad" min="1" class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-white">
                                    </div>

                                    <button type="button" @click="addNuevoProducto()" class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                                        Crear y Añadir
                                    </button>
                                </div>
                            </div>
                        </div>


                        <div class="mb-6 border-t pt-6 dark:border-gray-700" x-data="{
                            getProductosExistentes() {
                                return productosSeleccionados.filter(p => !p.es_nuevo);
                            },
                            getProductosNuevos() {
                                return productosSeleccionados.filter(p => p.es_nuevo);
                            }
                        }">
                            <h3 class="text-xl font-semibold mb-4">Productos en la Lista (<span x-text="productosSeleccionados.length"></span>)</h3>

                            <div class="space-y-3">
                                <template x-for="(producto, index) in productosSeleccionados" :key="producto.id_producto">
                                    <div class="flex items-center space-x-4 p-3 border rounded-md dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
                                        <div class="flex-1">
                                            <span x-text="producto.name" class="font-medium text-gray-900 dark:text-gray-100"></span>
                                            <span x-show="producto.es_nuevo" class="ml-2 text-xs bg-green-500 text-white px-2 py-1 rounded">NUEVO</span>
                                            <span x-show="producto.es_nuevo" x-text="'(' + producto.categoria_nombre + ')'" class="ml-1 text-sm text-gray-500 dark:text-gray-400"></span>
                                        </div>
                                        
                                        <!-- Para productos existentes -->
                                        <template x-if="!producto.es_nuevo">
                                            <div>
                                                <input type="hidden" :name="'productos[' + getProductosExistentes().indexOf(producto) + '][producto_id]'" :value="producto.id_producto">
                                            </div>
                                        </template>
                                        
                                        <!-- Para productos nuevos -->
                                        <template x-if="producto.es_nuevo">
                                            <div>
                                                <input type="hidden" :name="'nuevos_productos[' + getProductosNuevos().indexOf(producto) + '][nombre]'" :value="producto.name">
                                                <input type="hidden" :name="'nuevos_productos[' + getProductosNuevos().indexOf(producto) + '][categoria_id]'" :value="producto.id_categoria">
                                            </div>
                                        </template>

                                        <div class="w-1/5">
                                            <input type="number"
                                                :name="producto.es_nuevo ? 'nuevos_productos[' + getProductosNuevos().indexOf(producto) + '][cantidad]' : 'productos[' + getProductosExistentes().indexOf(producto) + '][cantidad]'"
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
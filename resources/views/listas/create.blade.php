<x-guest-layout>
    <x-slot name="navbar">
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 bg-white/10 text-white font-semibold rounded-2xl backdrop-blur-xl hover:bg-white/20 transition flex items-center space-x-2">
                🏠 <span>Inicio</span>
            </a>

            <a href="{{ route('listas.propias') }}"
               class="px-4 py-2 bg-white/10 text-white font-semibold rounded-2xl backdrop-blur-xl hover:bg-white/20 transition flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Mis Listas</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12 flex justify-center px-3 sm:px-4">
        <div class="w-full max-w-4xl bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl shadow-xl p-4 sm:p-6 lg:p-8 text-white">

            <h2 class="text-2xl sm:text-3xl font-semibold mb-4 sm:mb-6 tracking-wide">Crear Nueva Lista</h2>

            <form action="{{ route('listas.store') }}" method="POST" class="space-y-4 sm:space-y-6"
                x-data="{
                    categoriasMaestras: {{ App\Models\Categoria::with('productos')->get()->toJson() }},
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
                            Swal.fire({
                                title: 'Producto duplicado',
                                text: 'Este producto ya ha sido añadido a la lista.',
                                icon: 'warning'
                            });
                            return;
                        }
                        this.productosSeleccionados.push({
                            id_producto: newProd.id_producto,
                            name: newProd.name,
                            cantidad: this.productoAAnadirCantidad,
                            precio: newProd.precio || 0,
                            id_categoria: newProd.id_categoria,
                            unidad_medida: newProd.unidad_medida,
                            es_editable: newProd.created_by_user_id ? true : false, // Solo editable si fue creado por usuario
                            es_nuevo: false
                        });
                        this.productoAAnadirId = '';
                        this.productoAAnadirCantidad = 1;
                    }
                }">
                @csrf

                <!-- Título -->
                <div>
                    <label for="name" class="block text-sm font-semibold mb-2">TÍTULO</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-4 py-2 placeholder-gray-300 focus:border-purple-400 focus:ring-purple-400 transition">
                </div>

                <!-- Descripción -->
                <div>
                    <label for="description" class="block text-sm font-semibold mb-2">DESCRIPCIÓN</label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-4 py-2 placeholder-gray-300 focus:border-purple-400 focus:ring-purple-400 transition">{{ old('description') }}</textarea>
                </div>

                <!-- Añadir Productos -->
                <div class="p-3 sm:p-4 lg:p-6 rounded-xl border border-white/20 bg-white/10 backdrop-blur-xl shadow-lg">
                    <h3 class="text-xl sm:text-2xl font-semibold mb-3 sm:mb-4 tracking-wide">Añadir Productos</h3>

                    <!-- Productos existentes -->
                    <div class="mb-3 sm:mb-4">
                        <h4 class="text-base sm:text-lg font-medium mb-2 sm:mb-3">Desde productos existentes</h4>
                        <div class="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3 lg:gap-4 items-stretch sm:items-end">
                            <div class="flex-1 min-w-[150px]">
                                <label class="block text-sm font-semibold mb-1">Categoría</label>
                                <select x-model="categoriaActual" @change="filtrarProductos()"
                                    class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-3 py-2">
                                    <option value="">-- Selecciona Categoría --</option>
                                    <template x-for="cat in categoriasMaestras" :key="cat.id_categoria">
                                        <option :value="cat.id_categoria" x-text="cat.nombre"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="flex-1 min-w-[150px]">
                                <label class="block text-sm font-semibold mb-1">Producto</label>
                                <select x-model="productoAAnadirId" :disabled="!categoriaActual"
                                    class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-3 py-2">
                                    <option value="">-- Selecciona Producto --</option>
                                    <template x-for="prod in productosFiltrados" :key="prod.id_producto">
                                        <option :value="prod.id_producto" x-text="prod.name"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="w-full sm:w-1/6 min-w-[80px]">
                                <label class="block text-sm font-semibold mb-1">Cantidad</label>
                                <input type="number" min="1" x-model.number="productoAAnadirCantidad"
                                    class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-3 py-2">
                            </div>

                            <button type="button" @click="addProducto()"
                                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-xl shadow-md transition w-full sm:w-auto">Añadir</button>
                        </div>
                    </div>

                    <!-- Crear nuevo producto -->
                    <div x-data="{
                        nuevoProductoNombre: '',
                        nuevoProductoCategoria: '',
                        nuevoProductoCantidad: 1,
                        nuevoProductoPrecio: 0,
                        addNuevoProducto() {
                            if (!this.nuevoProductoNombre.trim() || !this.nuevoProductoCategoria) {
                                Swal.fire({
                                    title: 'Datos incompletos',
                                    text: 'Por favor, ingresa el nombre del producto y selecciona una categoría.',
                                    icon: 'warning'
                                });
                                return;
                            }
                            const cat = categoriasMaestras.find(c => c.id_categoria == this.nuevoProductoCategoria);
                            productosSeleccionados.push({
                                id_producto: 'nuevo_' + Date.now(),
                                name: this.nuevoProductoNombre.trim(),
                                cantidad: this.nuevoProductoCantidad,
                                precio: this.nuevoProductoPrecio || 0,
                                id_categoria: this.nuevoProductoCategoria,
                                categoria_nombre: cat.nombre,
                                es_nuevo: true,
                                es_editable: true // Los productos nuevos siempre son editables
                            });
                            this.nuevoProductoNombre = '';
                            this.nuevoProductoCategoria = '';
                            this.nuevoProductoCantidad = 1;
                            this.nuevoProductoPrecio = 0;
                        }
                    }" class="space-y-3">
                        <h4 class="text-lg font-medium mb-3">Crear nuevo producto</h4>
                        <div class="flex flex-wrap gap-4 items-end">
                            <div class="flex-1 min-w-[150px]">
                                <input type="text" placeholder="Nombre del Producto" x-model="nuevoProductoNombre"
                                    class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-4 py-2">
                            </div>
                            <div class="flex-1 min-w-[150px]">
                                <select x-model="nuevoProductoCategoria"
                                    class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-3 py-2">
                                    <option value="">-- Selecciona Categoría --</option>
                                    <template x-for="cat in categoriasMaestras" :key="cat.id_categoria">
                                        <option :value="cat.id_categoria" x-text="cat.nombre"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="w-1/6 min-w-[80px]">
                                <label class="block text-sm font-semibold mb-1">Cantidad</label>
                                <input type="number" min="1" x-model.number="nuevoProductoCantidad"
                                    class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-3 py-2">
                            </div>
                            <div class="w-1/5 min-w-[100px]">
                                <label class="block text-sm font-semibold mb-1">Precio (€)</label>
                                <input type="number" step="0.01" min="0" x-model.number="nuevoProductoPrecio"
                                    class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-3 py-2">
                            </div>
                            <button type="button" @click="addNuevoProducto()"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-xl shadow-md transition">Crear y Añadir</button>
                        </div>
                    </div>
                </div>

                <!-- Lista de productos añadidos -->
                <div class="space-y-3 border-t border-white/20 pt-4"
                    x-data="{
                        getProductosExistentes() {
                            return productosSeleccionados.filter(p => !p.es_nuevo);
                        },
                        getProductosNuevos() {
                            return productosSeleccionados.filter(p => p.es_nuevo);
                        }
                    }">
                    <h3 class="text-2xl font-semibold mb-4 tracking-wide">Productos en la Lista (<span x-text="productosSeleccionados.length"></span>)</h3>
                    <div class="space-y-3">
                        <template x-for="(producto, index) in productosSeleccionados" :key="producto.id_producto">
                            <div class="flex items-center space-x-2 sm:space-x-4 p-3 rounded-xl border border-white/20 bg-white/10 backdrop-blur-xl shadow-md">
                                <div class="flex-1 min-w-0">
                                    <span x-text="producto.name" class="font-semibold tracking-wide truncate block"></span>
                                    <span x-show="producto.es_nuevo" class="ml-2 text-xs bg-green-500 text-white px-2 py-1 rounded-lg">NUEVO</span>
                                    <span x-show="producto.es_nuevo" x-text="'(' + producto.categoria_nombre + ')'" class="text-sm ml-1 opacity-70"></span>
                                </div>

                                <template x-if="!producto.es_nuevo">
                                    <div>
                                        <input type="hidden" :name="'productos[' + getProductosExistentes().indexOf(producto) + '][producto_id]'" :value="producto.id_producto">
                                        <input type="hidden" x-show="producto.es_editable" :name="'productos[' + getProductosExistentes().indexOf(producto) + '][precio]'" :value="producto.precio">
                                    </div>
                                </template>
                                <template x-if="producto.es_nuevo">
                                    <div>
                                        <input type="hidden" :name="'nuevos_productos[' + getProductosNuevos().indexOf(producto) + '][nombre]'" :value="producto.name">
                                        <input type="hidden" :name="'nuevos_productos[' + getProductosNuevos().indexOf(producto) + '][categoria_id]'" :value="producto.id_categoria">
                                        <input type="hidden" :name="'nuevos_productos[' + getProductosNuevos().indexOf(producto) + '][precio]'" :value="producto.precio">
                                    </div>
                                </template>

                                <div class="w-20 sm:w-24">
                                    <label class="block text-xs text-gray-300 mb-1">Cant.</label>
                                    <input type="number" min="1" required x-model.number="producto.cantidad"
                                        :name="producto.es_nuevo
                                            ? 'nuevos_productos[' + getProductosNuevos().indexOf(producto) + '][cantidad]'
                                            : 'productos[' + getProductosExistentes().indexOf(producto) + '][cantidad]'"
                                        class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-2 py-2 text-center">
                                </div>

                                <div class="w-24 sm:w-28">
                                    <label class="block text-xs text-gray-300 mb-1">Precio</label>
                                    <input type="number" step="0.01" min="0" x-model.number="producto.precio"
                                        :readonly="!producto.es_editable"
                                        :class="!producto.es_editable ? 'bg-gray-700/50 cursor-not-allowed' : 'bg-white/10'"
                                        :title="!producto.es_editable ? 'Precio fijo del sistema' : 'Precio editable'"
                                        class="w-full rounded-xl border border-white/20 text-white px-2 py-2 text-center">
                                </div>

                                <button type="button" @click="productosSeleccionados.splice(index, 1)" class="text-red-400 hover:text-red-600 transition text-xl">✕</button>
                            </div>
                        </template>

                        <p x-show="productosSeleccionados.length === 0" class="opacity-70 italic">No hay productos seleccionados.</p>
                    </div>
                </div>

                <!-- Botón Crear Lista -->
                <div class="flex justify-end mt-4 sm:mt-6">
                    <button type="submit" class="px-4 sm:px-5 py-2 sm:py-3 bg-purple-600 hover:bg-purple-700 border border-white/20 rounded-xl font-semibold shadow-md transition text-sm sm:text-base">
                        Crear Lista
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-guest-layout>

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

    <div class="py-12 flex justify-center px-4">
        <div class="w-full max-w-4xl bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl shadow-xl p-8 text-white">

            <h2 class="text-3xl font-semibold mb-6 tracking-wide">Crear Nueva Lista</h2>

            <form action="{{ route('listas.store') }}" method="POST" class="space-y-6"
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
                <div class="p-6 rounded-xl border border-white/20 bg-white/10 backdrop-blur-xl shadow-lg">
                    <h3 class="text-2xl font-semibold mb-4 tracking-wide">Añadir Productos</h3>

                    <!-- Productos existentes -->
                    <div class="mb-4">
                        <h4 class="text-lg font-medium mb-3">Desde productos existentes</h4>
                        <div class="flex flex-wrap gap-4 items-end">
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

                            <div class="w-1/6 min-w-[80px]">
                                <label class="block text-sm font-semibold mb-1">Cantidad</label>
                                <input type="number" min="1" x-model.number="productoAAnadirCantidad"
                                    class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-3 py-2">
                            </div>

                            <button type="button" @click="addProducto()"
                                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-xl shadow-md transition">Añadir</button>
                        </div>
                    </div>

                    <!-- Crear nuevo producto -->
                    <div x-data="{
                        nuevoProductoNombre: '',
                        nuevoProductoCategoria: '',
                        nuevoProductoCantidad: 1,
                        addNuevoProducto() {
                            if (!this.nuevoProductoNombre.trim() || !this.nuevoProductoCategoria) {
                                alert('Ingresa nombre y categoría.');
                                return;
                            }
                            const cat = categoriasMaestras.find(c => c.id_categoria == this.nuevoProductoCategoria);
                            productosSeleccionados.push({
                                id_producto: 'nuevo_' + Date.now(),
                                name: this.nuevoProductoNombre.trim(),
                                cantidad: this.nuevoProductoCantidad,
                                id_categoria: this.nuevoProductoCategoria,
                                categoria_nombre: cat.nombre,
                                es_nuevo: true
                            });
                            this.nuevoProductoNombre = '';
                            this.nuevoProductoCategoria = '';
                            this.nuevoProductoCantidad = 1;
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
                                <input type="number" min="1" x-model.number="nuevoProductoCantidad"
                                    class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-3 py-2">
                            </div>
                            <button type="button" @click="addNuevoProducto()"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-xl shadow-md transition">Crear y Añadir</button>
                        </div>
                    </div>
                </div>

                <!-- Lista de productos añadidos -->
                <div class="space-y-3 border-t border-white/20 pt-4">
                    <h3 class="text-2xl font-semibold mb-4 tracking-wide">Productos en la Lista (<span x-text="productosSeleccionados.length"></span>)</h3>
                    <div class="space-y-3">
                        <template x-for="(producto, index) in productosSeleccionados" :key="producto.id_producto">
                            <div class="flex items-center space-x-4 p-3 rounded-xl border border-white/20 bg-white/10 backdrop-blur-xl shadow-md">
                                <div class="flex-1">
                                    <span x-text="producto.name" class="font-semibold tracking-wide"></span>
                                    <span x-show="producto.es_nuevo" class="ml-2 text-xs bg-green-500 text-white px-2 py-1 rounded-lg">NUEVO</span>
                                    <span x-show="producto.es_nuevo" x-text="'(' + producto.categoria_nombre + ')'" class="text-sm ml-1 opacity-70"></span>
                                </div>

                                <template x-if="!producto.es_nuevo">
                                    <input type="hidden" :name="'productos[' + index + '][producto_id]'" :value="producto.id_producto">
                                </template>
                                <template x-if="producto.es_nuevo">
                                    <input type="hidden" :name="'nuevos_productos[' + index + '][nombre]'" :value="producto.name">
                                    <input type="hidden" :name="'nuevos_productos[' + index + '][categoria_id]'" :value="producto.id_categoria">
                                </template>

                                <div class="w-1/5">
                                    <input type="number" min="1" required x-model.number="producto.cantidad"
                                        :name="producto.es_nuevo ? 'nuevos_productos[' + index + '][cantidad]' : 'productos[' + index + '][cantidad]'"
                                        class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-3 py-2">
                                </div>

                                <button type="button" @click="productosSeleccionados.splice(index, 1)" class="text-red-400 hover:text-red-600 transition">✕</button>
                            </div>
                        </template>

                        <p x-show="productosSeleccionados.length === 0" class="opacity-70 italic">No hay productos seleccionados.</p>
                    </div>
                </div>

                <!-- Botón Crear Lista -->
                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-5 py-3 bg-purple-600 hover:bg-purple-700 border border-white/20 rounded-xl font-semibold shadow-md transition">
                        Crear Lista
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-guest-layout>

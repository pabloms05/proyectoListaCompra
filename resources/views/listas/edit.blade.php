<x-guest-layout>
    <x-slot name="navbar">
        <div class="flex items-center space-x-4">
            <!-- Botón Mis Listas -->
            <a href="{{ route('listas.show', $lista) }}"
               class="px-4 py-2 bg-white/10 text-white font-semibold rounded-2xl backdrop-blur-xl hover:bg-white/20 transition-colors flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Volver a la Lista</span>
            </a>

            <!-- Botón Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 bg-white/10 text-white font-semibold rounded-2xl backdrop-blur-xl hover:bg-white/20 transition-colors flex items-center space-x-2">
                🏠 <span>Inicio</span>
            </a>
        </div>
    </x-slot> 
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- CONTENEDOR GLASS OSCURO -->
            <div class="bg-white/10 border border-white/20 backdrop-blur-xl shadow-xl rounded-2xl">
                <div class="p-8 text-white">

                    <h2 class="text-3xl font-semibold mb-8 tracking-wide">Editar Lista: {{ $lista->name }}</h2>

                    <form action="{{ route('listas.update', $lista) }}" method="POST" class="max-w-4xl"
                        x-data="{
                            categoriasMaestras: {{ $categoriasMaestras->toJson() }},
                            productosSeleccionados: {{ $productosLista->map(function($p) {
                                return [
                                    'id_producto' => $p->id_producto,
                                    'name' => $p->name,
                                    'cantidad' => $p->pivot->cantidad,
                                    'categoria_id' => $p->categoria_id,
                                ];
                            })->toJson() }},
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
                                    categoria_id: newProd.categoria_id
                                });

                                this.productoAAnadirId = '';
                                this.productoAAnadirCantidad = 1;
                            }
                        }">

                        @csrf
                        @method('PUT')

                        <!-- TITULO -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-semibold mb-2">TÍTULO</label>
                            <input type="text" id="name" name="name"
                                value="{{ old('name', $lista->name) }}" required
                                class="w-full rounded-xl border border-white/20 bg-white/10 text-white 
                                       placeholder-gray-300 px-4 py-2 focus:border-purple-400 focus:ring-purple-400 transition">
                        </div>

                        <!-- DESCRIPCION -->
                        <div class="mb-8">
                            <label for="description" class="block text-sm font-semibold mb-2">DESCRIPCIÓN</label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full rounded-xl border border-white/20 bg-white/10 text-white 
                                       placeholder-gray-300 px-4 py-2 focus:border-purple-400 focus:ring-purple-400 transition">{{ old('description', $lista->description) }}</textarea>
                        </div>

                        <!-- CONTENEDOR GLASS DE AÑADIR PRODUCTOS -->
                        <div class="p-6 mb-10 rounded-xl border border-white/20 bg-white/10 backdrop-blur-xl shadow-lg">

                            <h3 class="text-2xl font-semibold mb-6 tracking-wide">Añadir Productos</h3>

                            <!-- PRODUCTOS EXISTENTES -->
                            <div class="mb-6">
                                <h4 class="text-lg font-medium mb-3">Desde productos existentes</h4>

                                <div class="flex space-x-4 items-end">

                                    <!-- CATEGORÍA -->
                                    <div class="flex-1">
                                        <label class="block text-sm font-semibold mb-1">Categoría</label>
                                        <select x-model="categoriaActual" @change="filtrarProductos()"
                                            class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-3 py-2">
                                            <option value="" class="bg-gray-800">-- Selecciona Categoría --</option>
                                            <template x-for="cat in categoriasMaestras" :key="cat.id_categoria">
                                                <option :value="cat.id_categoria" x-text="cat.nombre" class="bg-gray-800"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <!-- PRODUCTO -->
                                    <div class="flex-1">
                                        <label class="block text-sm font-semibold mb-1">Producto</label>
                                        <select x-model="productoAAnadirId" :disabled="!categoriaActual"
                                            class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-3 py-2">
                                            <option value="" class="bg-gray-800">-- Selecciona Producto --</option>
                                            <template x-for="prod in productosFiltrados" :key="prod.id_producto">
                                                <option :value="prod.id_producto" x-text="prod.name" class="bg-gray-800"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <!-- CANTIDAD -->
                                    <div class="w-1/6">
                                        <label class="block text-sm font-semibold mb-1">Cantidad</label>
                                        <input type="number" min="1" x-model.number="productoAAnadirCantidad"
                                            class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-3 py-2">
                                    </div>

                                    <!-- BOTÓN AÑADIR -->
                                    <button type="button" @click="addProducto()"
                                        class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 
                                               shadow-md transition">Añadir</button>
                                </div>
                            </div>

                            <!-- SEPARADOR -->
                            <div class="my-6 border-t border-white/20"></div>

                            <!-- NUEVO PRODUCTO -->
                            <div x-data="{
                                nuevoProductoNombre: '',
                                nuevoProductoCategoria: '',
                                nuevoProductoCantidad: 1,

                                addNuevoProducto() {
                                    if (!this.nuevoProductoNombre.trim() || !this.nuevoProductoCategoria) {
                                        alert('Por favor, ingresa un nombre y selecciona una categoría.');
                                        return;
                                    }

                                    const cat = categoriasMaestras.find(c => c.id_categoria == this.nuevoProductoCategoria);

                                    productosSeleccionados.push({
                                        id_producto: 'nuevo_' + Date.now(),
                                        name: this.nuevoProductoNombre.trim(),
                                        cantidad: this.nuevoProductoCantidad,
                                        categoria_id: this.nuevoProductoCategoria,
                                        categoria_nombre: cat.nombre,
                                        es_nuevo: true
                                    });

                                    this.nuevoProductoNombre = '';
                                    this.nuevoProductoCategoria = '';
                                    this.nuevoProductoCantidad = 1;
                                }
                            }">

                                <h4 class="text-lg font-medium mb-3">Crear nuevo producto</h4>

                                <div class="flex space-x-4 items-end">

                                    <!-- NOMBRE -->
                                    <div class="flex-1">
                                        <label class="block text-sm font-semibold mb-1">Nombre</label>
                                        <input type="text" x-model="nuevoProductoNombre"
                                            class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-4 py-2">
                                    </div>

                                    <!-- CATEGORIA -->
                                    <div class="flex-1">
                                        <label class="block text-sm font-semibold mb-1">Categoría</label>
                                        <select x-model="nuevoProductoCategoria"
                                            class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-3 py-2">
                                            <option value="">-- Selecciona Categoría --</option>
                                            <template x-for="cat in categoriasMaestras" :key="cat.id_categoria">
                                                <option :value="cat.id_categoria" x-text="cat.nombre"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <!-- CANTIDAD -->
                                    <div class="w-1/6">
                                        <label class="block text-sm font-semibold mb-1">Cantidad</label>
                                        <input type="number" min="1" x-model.number="nuevoProductoCantidad"
                                            class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-3 py-2">
                                    </div>

                                    <button type="button" @click="addNuevoProducto()"
                                        class="px-4 py-2 rounded-xl bg-green-600 hover:bg-green-700 shadow-md transition">
                                        Crear y Añadir
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- LISTA DE PRODUCTOS -->
                        <div class="mb-6 border-t border-white/20 pt-6"
                            x-data="{
                                getProductosExistentes() {
                                    return productosSeleccionados.filter(p => !p.es_nuevo);
                                },
                                getProductosNuevos() {
                                    return productosSeleccionados.filter(p => p.es_nuevo);
                                }
                            }">

                            <h3 class="text-2xl font-semibold mb-4 tracking-wide">
                                Productos en la Lista (<span x-text="productosSeleccionados.length"></span>)
                            </h3>

                            <div class="space-y-4">

                                <template x-for="(producto, index) in productosSeleccionados" :key="producto.id_producto">
                                    <div class="flex items-center space-x-4 p-4 rounded-xl border border-white/20 
                                                bg-white/10 backdrop-blur-xl shadow-md">

                                        <div class="flex-1">
                                            <span x-text="producto.name"
                                                class="font-semibold tracking-wide"></span>
                                            
                                            <!-- NUEVO TAG -->
                                            <span x-show="producto.es_nuevo"
                                                class="ml-2 text-xs bg-green-500 text-white px-2 py-1 rounded-lg">
                                                NUEVO
                                            </span>

                                            <span x-show="producto.es_nuevo"
                                                x-text="'(' + producto.categoria_nombre + ')'"
                                                class="text-sm ml-1 opacity-70"></span>
                                        </div>

                                        <!-- HIDDEN INPUTS -->
                                        <template x-if="!producto.es_nuevo">
                                            <input type="hidden"
                                                :name="'productos[' + getProductosExistentes().indexOf(producto) + '][producto_id]'"
                                                :value="producto.id_producto">
                                        </template>

                                        <template x-if="producto.es_nuevo">
                                            <div>
                                                <input type="hidden"
                                                    :name="'nuevos_productos[' + getProductosNuevos().indexOf(producto) + '][nombre]'"
                                                    :value="producto.name">

                                                <input type="hidden"
                                                    :name="'nuevos_productos[' + getProductosNuevos().indexOf(producto) + '][categoria_id]'"
                                                    :value="producto.categoria_id">
                                            </div>
                                        </template>

                                        <!-- CANTIDAD -->
                                        <div class="w-1/5">
                                            <input type="number" min="1"
                                                x-model.number="producto.cantidad"
                                                :name="producto.es_nuevo
                                                    ? 'nuevos_productos[' + getProductosNuevos().indexOf(producto) + '][cantidad]'
                                                    : 'productos[' + getProductosExistentes().indexOf(producto) + '][cantidad]'"
                                                class="w-full rounded-xl border border-white/20 bg-gray-900/50 text-white px-3 py-2">
                                        </div>

                                        <!-- ELIMINAR -->
                                        <button type="button"
                                            @click="productosSeleccionados.splice(index, 1)"
                                            class="text-red-400 hover:text-red-600 transition">
                                            ✕
                                        </button>
                                    </div>
                                </template>

                                <p x-show="productosSeleccionados.length === 0"
                                    class="opacity-70 italic">No hay productos seleccionados.</p>

                            </div>
                        </div>

                        <!-- BOTONES FINALES -->
                        <div class="flex justify-between mt-10">

                            <a href="{{ route('listas.show', $lista) }}"
                                class="inline-flex items-center px-5 py-3 bg-gray-700/60 border border-white/20 
                                       rounded-xl text-white font-semibold hover:bg-gray-700 transition">

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>

                                Volver
                            </a>

                            <button type="submit"
                                class="px-5 py-3 bg-purple-600 hover:bg-purple-700 border border-white/20 
                                       rounded-xl font-semibold shadow-md transition">
                                Guardar Cambios
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>

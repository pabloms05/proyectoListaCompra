<x-guest-layout>
    <x-slot name="navbar">
        <div class="flex items-center space-x-4">
            <!-- Botón Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 bg-white/10 text-white font-semibold rounded-2xl backdrop-blur-xl hover:bg-white/20 transition-colors flex items-center space-x-2">
                🏠 <span>Inicio</span>
            </a>
            <!-- Botón Mis Listas -->
            <a href="{{ route('listas.show', $lista) }}"
               class="px-4 py-2 bg-white/10 text-white font-semibold rounded-2xl backdrop-blur-xl hover:bg-white/20 transition-colors flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Volver a la Lista</span>
            </a>
        </div>
    </x-slot> 
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">

            <!-- CONTENEDOR GLASS OSCURO -->
            <div class="bg-white/10 border border-white/20 backdrop-blur-xl shadow-xl rounded-2xl">
                <div class="p-4 sm:p-6 lg:p-8 text-white">

                    <h2 class="text-2xl sm:text-3xl font-semibold mb-4 sm:mb-6 lg:mb-8 tracking-wide">Editar Lista: {{ $lista->name }}</h2>

                    <form action="{{ route('listas.update', $lista) }}" method="POST" class="max-w-4xl"
                        x-data="{
                            categoriasMaestras: {{ $categoriasMaestras->toJson() }},
                            productosSeleccionados: {{ $productosLista->map(function($p) {
                                return [
                                    'id_producto' => $p->id_producto,
                                    'name' => $p->name,
                                    'cantidad' => $p->pivot->cantidad,
                                    'precio' => $p->pivot->precio_unitario ?? 0,
                                    'categoria_id' => $p->categoria_id,
                                    'es_editable' => $p->created_by_user_id ? true : false,
                                    'es_nuevo' => false,
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
                                    categoria_id: newProd.categoria_id,
                                    es_editable: newProd.created_by_user_id ? true : false,
                                    es_nuevo: false
                                });

                                this.productoAAnadirId = '';
                                this.productoAAnadirCantidad = 1;
                            }
                        }">

                        @csrf
                        @method('PUT')

                        <!-- TITULO -->
                        <div class="mb-4 sm:mb-6">
                            <label for="name" class="block text-xs sm:text-sm font-semibold mb-1 sm:mb-2">TÍTULO</label>
                            <input type="text" id="name" name="name"
                                value="{{ old('name', $lista->name) }}" required
                                class="w-full rounded-xl border border-white/20 bg-white/10 text-white 
                                       placeholder-gray-300 px-4 py-2 focus:border-purple-400 focus:ring-purple-400 transition">
                        </div>

                        <!-- DESCRIPCION -->
                        <div class="mb-4 sm:mb-6 lg:mb-8">
                            <label for="description" class="block text-xs sm:text-sm font-semibold mb-1 sm:mb-2">DESCRIPCIÓN</label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full rounded-xl border border-white/20 bg-white/10 text-white 
                                       placeholder-gray-300 px-4 py-2 focus:border-purple-400 focus:ring-purple-400 transition">{{ old('description', $lista->description) }}</textarea>
                        </div>

                        <!-- CONTENEDOR GLASS DE AÑADIR PRODUCTOS -->
                        <div class="p-3 sm:p-4 lg:p-6 mb-6 sm:mb-8 lg:mb-10 rounded-xl border border-white/20 bg-white/10 backdrop-blur-xl shadow-lg">

                            <h3 class="text-xl sm:text-2xl font-semibold mb-3 sm:mb-4 lg:mb-6 tracking-wide">Añadir Productos</h3>

                            <!-- PRODUCTOS EXISTENTES -->
                            <div class="mb-4 sm:mb-6">
                                <h4 class="text-base sm:text-lg font-medium mb-2 sm:mb-3">Desde productos existentes</h4>

                                <div class="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3 lg:gap-4 items-stretch sm:items-end">

                                    <!-- CATEGORÍA -->
                                    <div class="flex-1 min-w-[150px]">  
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
                                    <div class="flex-1 min-w-[150px]">
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
                                    <div class="w-full sm:w-1/6 min-w-[80px]">
                                        <label class="block text-sm font-semibold mb-1">Cantidad</label>
                                        <input type="number" min="1" x-model.number="productoAAnadirCantidad"
                                            class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-3 py-2">
                                    </div>

                                    <!-- BOTÓN AÑADIR -->
                                    <button type="button" @click="addProducto()"
                                        class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 
                                               shadow-md transition w-full sm:w-auto">Añadir</button>
                                </div>
                            </div>

                            <!-- SEPARADOR -->
                            <div class="my-6 border-t border-white/20"></div>

                            <!-- NUEVO PRODUCTO -->
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
                                        categoria_id: this.nuevoProductoCategoria,
                                        categoria_nombre: cat ? cat.nombre : '',
                                        es_nuevo: true,
                                        es_editable: true
                                    });

                                    this.nuevoProductoNombre = '';
                                    this.nuevoProductoCategoria = '';
                                    this.nuevoProductoCantidad = 1;
                                    this.nuevoProductoPrecio = 0;
                                }
                            }">

                                <h4 class="text-lg font-medium mb-3">Crear nuevo producto</h4>

                                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 items-stretch sm:items-end">

                                    <!-- NOMBRE -->
                                    <div class="flex-1 min-w-[150px]">
                                        <label class="block text-sm font-semibold mb-1">Nombre</label>
                                        <input type="text" x-model="nuevoProductoNombre"
                                            class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-4 py-2">
                                    </div>

                                    <!-- CATEGORIA -->
                                    <div class="flex-1 min-w-[150px]">
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
                                    <div class="w-full sm:w-1/6 min-w-[80px]">
                                        <label class="block text-sm font-semibold mb-1">Cantidad</label>
                                        <input type="number" min="1" x-model.number="nuevoProductoCantidad"
                                            class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-3 py-2">
                                    </div>

                                    <!-- PRECIO -->
                                    <div class="w-full sm:w-1/5 min-w-[100px]">
                                        <label class="block text-sm font-semibold mb-1">Precio (€)</label>
                                        <input type="number" step="0.01" min="0" x-model.number="nuevoProductoPrecio"
                                            class="w-full rounded-xl border border-white/20 bg-white/10 text-white px-3 py-2">
                                    </div>

                                    <button type="button" @click="addNuevoProducto()"
                                        class="px-4 py-2 rounded-xl bg-green-600 hover:bg-green-700 shadow-md transition w-full sm:w-auto">
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

                            <h3 class="text-xl sm:text-2xl font-semibold mb-3 sm:mb-4 tracking-wide">
                                Productos en la Lista (<span x-text="productosSeleccionados.length"></span>)
                            </h3>

                            <div class="space-y-4">

                                <template x-for="(producto, index) in productosSeleccionados" :key="producto.id_producto">
                                    <div class="flex items-center space-x-4 p-4 rounded-xl border border-white/20 
                                                bg-white/10 backdrop-blur-xl shadow-md">

                                        <div class="flex-1 min-w-0">
                                            <span x-text="producto.name"
                                                class="font-semibold tracking-wide truncate block"></span>
                                            
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
                                            <div>
                                                <input type="hidden"
                                                    :name="'productos[' + getProductosExistentes().indexOf(producto) + '][producto_id]'"
                                                    :value="producto.id_producto">
                                            </div>
                                        </template>

                                        <template x-if="producto.es_nuevo">
                                            <div>
                                                <input type="hidden"
                                                    :name="'nuevos_productos[' + getProductosNuevos().indexOf(producto) + '][nombre]'"
                                                    :value="producto.name">

                                                <input type="hidden"
                                                    :name="'nuevos_productos[' + getProductosNuevos().indexOf(producto) + '][categoria_id]'"
                                                    :value="producto.categoria_id">
                                                
                                                <input type="hidden"
                                                    :name="'nuevos_productos[' + getProductosNuevos().indexOf(producto) + '][precio]'"
                                                    :value="producto.precio">
                                            </div>
                                        </template>

                                        <!-- CANTIDAD -->
                                        <div class="w-20 sm:w-24">
                                            <label class="block text-xs text-gray-300 mb-1">Cant.</label>
                                            <input type="number" min="1"
                                                x-model.number="producto.cantidad"
                                                :name="producto.es_nuevo
                                                    ? 'nuevos_productos[' + getProductosNuevos().indexOf(producto) + '][cantidad]'
                                                    : 'productos[' + getProductosExistentes().indexOf(producto) + '][cantidad]'"
                                                class="w-full rounded-xl border border-white/20 bg-gray-900/50 text-white px-2 py-2 text-center">
                                        </div>

                                        <!-- PRECIO -->
                                        <div class="w-24 sm:w-28">
                                            <label class="block text-xs text-gray-300 mb-1">Precio</label>
                                            <input type="number" step="0.01" min="0"
                                                x-model.number="producto.precio"
                                                :readonly="!producto.es_editable"
                                                :class="producto.es_editable ? 'bg-gray-900/50' : 'bg-gray-600/30 cursor-not-allowed text-gray-400'"
                                                class="w-full rounded-xl border border-white/20 px-2 py-2 text-center"
                                                :name="producto.es_editable && !producto.es_nuevo 
                                                    ? 'productos[' + getProductosExistentes().indexOf(producto) + '][precio]' 
                                                    : ''">
                                        </div>

                                        <!-- ELIMINAR -->
                                        <button type="button"
                                            @click="productosSeleccionados.splice(index, 1)"
                                            class="text-red-400 hover:text-red-600 transition text-xl">
                                            ✕
                                        </button>
                                    </div>
                                </template>

                                <p x-show="productosSeleccionados.length === 0"
                                    class="opacity-70 italic">No hay productos seleccionados.</p>

                            </div>
                        </div>

                        <!-- BOTONES FINALES -->
                        <div class="flex flex-col sm:flex-row justify-between gap-3 mt-6 sm:mt-8 lg:mt-10">

                            <a href="{{ route('listas.show', $lista) }}"
                                class="inline-flex items-center justify-center px-3 sm:px-5 py-2 sm:py-3 bg-gray-700/60 border border-white/20 
                                       rounded-xl font-semibold hover:bg-gray-600/60 shadow-md transition text-sm sm:text-base">

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>

                                Volver
                            </a>

                            <button type="submit"
                                class="px-3 sm:px-5 py-2 sm:py-3 bg-purple-600 hover:bg-purple-700 border border-white/20 
                                       rounded-xl font-semibold shadow-md transition text-sm sm:text-base">
                                Guardar Cambios
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>

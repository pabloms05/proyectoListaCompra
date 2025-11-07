<!-- Modal Compartir Lista -->
<div id="modalCompartir" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Encabezado -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    Compartir Lista
                </h3>
                <button onclick="cerrarModalCompartir()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Formulario -->
            <form id="formularioCompartir" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Buscar usuario
                    </label>
                    <input 
                        type="text" 
                        id="buscarUsuario" 
                        placeholder="Nombre o email..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        autocomplete="off"
                    >
                    <!-- Resultados de búsqueda -->
                    <div id="resultadosBusqueda" class="mt-2 hidden">
                        <div class="border border-gray-200 rounded-md max-h-48 overflow-y-auto">
                            <!-- Se llenará con JavaScript -->
                        </div>
                    </div>
                    <input type="hidden" id="idUsuarioSeleccionado" name="id_usuario">
                    <div id="mostrarUsuarioSeleccionado" class="mt-2 hidden">
                        <div class="flex items-center justify-between bg-blue-50 p-2 rounded-md">
                            <span id="nombreUsuarioSeleccionado" class="text-sm text-gray-700"></span>
                            <button type="button" onclick="limpiarSeleccionUsuario()" class="text-red-500 text-sm">
                                Cambiar
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de acceso
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input 
                                type="radio" 
                                name="rol" 
                                value="editor" 
                                checked
                                class="mr-2"
                            >
                            <div>
                                <div class="font-medium">Editor</div>
                                <div class="text-xs text-gray-500">Puede ver y modificar la lista</div>
                            </div>
                        </label>
                        <label class="flex items-center">
                            <input 
                                type="radio" 
                                name="rol" 
                                value="lector"
                                class="mr-2"
                            >
                            <div>
                                <div class="font-medium">Lector</div>
                                <div class="text-xs text-gray-500">Solo puede ver la lista</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Mensajes -->
                <div id="mensajeCompartir" class="hidden"></div>

                <!-- Botones -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button 
                        type="button" 
                        onclick="cerrarModalCompartir()"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                        id="botonCompartir"
                    >
                        Compartir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
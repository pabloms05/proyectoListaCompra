<!-- Sección de Usuarios Compartidos -->
<div class="mt-6 bg-white rounded-lg shadow p-4">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        👥 Compartida con
    </h3>
    
    <div id="listaUsuariosCompartidos">
        <!-- Se llenará con JavaScript -->
        <p class="text-gray-500 text-sm">Cargando...</p>
    </div>
</div>

<!-- Plantilla para cada usuario -->
<template id="plantillaUsuarioCompartido">
    <div class="flex items-center justify-between py-3 border-b last:border-b-0">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                <span class="iniciales-usuario"></span>
            </div>
            <div>
                <div class="font-medium text-gray-800 nombre-usuario"></div>
                <div class="text-sm text-gray-500 email-usuario"></div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <select class="selector-rol text-sm border border-gray-300 rounded-md px-2 py-1">
                <option value="editor">Editor</option>
                <option value="lector">Lector</option>
            </select>
            <button class="boton-revocar text-red-500 hover:text-red-700 text-sm px-3 py-1">
                Revocar
            </button>
        </div>
    </div>
</template>
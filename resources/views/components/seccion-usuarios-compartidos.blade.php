<!-- Sección de Usuarios Compartidos -->
<div class="mt-3 sm:mt-4 bg-gray-800/70 rounded-lg shadow p-2 sm:p-3 border border-gray-700">
    <h3 class="text-sm sm:text-base font-semibold text-white mb-2 sm:mb-3">
        👥 Compartida con
    </h3>
    
    <div id="listaUsuariosCompartidos">
        <!-- Se llenará con JavaScript -->
        <p class="text-gray-300 text-xs sm:text-sm">Cargando...</p>
    </div>
</div>

<!-- Plantilla para cada usuario -->
<template id="plantillaUsuarioCompartido">
    <div class="flex items-center justify-between py-2 border-b border-gray-600 last:border-b-0 gap-2">
        <div class="flex items-center space-x-2 min-w-0 flex-1">
            <div class="w-8 h-8 sm:w-9 sm:h-9 bg-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                <span class="iniciales-usuario"></span>
            </div>
            <div class="min-w-0 flex-1">
                <div class="font-medium text-white nombre-usuario text-xs sm:text-sm truncate"></div>
                <div class="text-xs text-gray-300 email-usuario truncate"></div>
            </div>
        </div>
        <div class="flex items-center space-x-1 sm:space-x-2 flex-shrink-0">
            <select class="selector-rol text-xs bg-gray-700 text-white border border-gray-600 rounded-md px-1.5 py-0.5 sm:px-2 sm:py-1 hover:bg-gray-600 transition">
                <option value="editor">Editor</option>
                <option value="lector">Lector</option>
            </select>
            <button class="boton-revocar bg-red-600 hover:bg-red-700 text-white text-xs px-2 py-0.5 sm:px-3 sm:py-1 rounded-md font-semibold transition whitespace-nowrap">
                Revocar
            </button>
        </div>
    </div>
</template>
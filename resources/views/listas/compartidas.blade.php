<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📥 Listas Compartidas Conmigo
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div id="contenedorListasCompartidas">
                        <p class="text-gray-500">Cargando...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Cargando listas compartidas...');
        
        fetch('/listas/compartidas')
            .then(respuesta => {
                console.log('Respuesta recibida:', respuesta);
                return respuesta.json();
            })
            .then(listas => {
                console.log('Listas:', listas);
                const contenedor = document.getElementById('contenedorListasCompartidas');
                
                if (listas.length === 0) {
                    contenedor.innerHTML = `
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-lg">No tienes listas compartidas</p>
                        </div>
                    `;
                    return;
                }
                
                contenedor.innerHTML = listas.map(lista => `
                    <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6 mb-4">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                    ${lista.nombre}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 mt-1">
                                    ${lista.descripcion || 'Sin descripción'}
                                </p>
                                <div class="mt-3 flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                    <span>👤 De: ${lista.propietario.nombre}</span>
                                    <span>📝 Rol: ${lista.rol === 'editor' ? 'Editor' : 'Lector'}</span>
                                    <span>📅 ${new Date(lista.fecha_compartida).toLocaleDateString()}</span>
                                </div>
                            </div>
                            <a href="/listas/${lista.id}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                Ver Lista
                            </a>
                        </div>
                    </div>
                `).join('');
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('contenedorListasCompartidas').innerHTML = `
                    <p class="text-red-500">Error al cargar las listas: ${error.message}</p>
                `;
            });
    });
    </script>
</x-app-layout>
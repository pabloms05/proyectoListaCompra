<x-app-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-3xl font-bold text-center mb-8">Gestión de Listas de Compra</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                    <!-- Mis Listas -->
                    <a href="{{ route('listas.propias') }}" class="transform transition-all hover:scale-105">
                        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-xl p-6 text-white text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h2 class="text-2xl font-bold mb-2">Mis Listas</h2>
                            <p class="text-gray-100">Gestiona tus listas de compra personales</p>
                        </div>
                    </a>

                    <!-- Listas Compartidas -->
                    <a href="{{ route('listas.compartidas') }}" class="transform transition-all hover:scale-105">
                        <div class="bg-gradient-to-br from-green-500 to-teal-600 rounded-lg shadow-xl p-6 text-white text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                            </svg>
                            <h2 class="text-2xl font-bold mb-2">Listas Compartidas</h2>
                            <p class="text-gray-100">Accede a las listas compartidas contigo</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>

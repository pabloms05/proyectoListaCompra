<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-3xl font-bold">Listas Compartidas</h1>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($listas as $lista)
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg overflow-hidden transform transition-all hover:scale-105">
                                <div class="p-5">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-xl font-semibold">{{ $lista->name }}</h3>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            Compartida por: {{ $lista->owner->name }}
                                        </span>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-300 mb-4 h-12 overflow-hidden">{{ $lista->description }}</p>
                                    
                                    <div class="border-t dark:border-gray-600 pt-4">
                                        <div class="flex justify-between items-center">
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                <span>{{ $lista->productos_count ?? 0 }} productos</span>
                                                <span class="mx-2">•</span>
                                                <span>{{ $lista->categorias_count ?? 0 }} categorías</span>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('listas.show', $lista) }}" 
                                                   class="inline-flex items-center px-3 py-1 bg-indigo-600 rounded-md text-xs font-semibold text-white hover:bg-indigo-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    Ver
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                </svg>
                                <p class="text-xl text-gray-500 dark:text-gray-400">No tienes listas compartidas</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
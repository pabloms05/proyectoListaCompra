<x-guest-layout>
    <style>
        body {
            background: linear-gradient(135deg, #4e4376, #2b5876) !important;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            background-attachment: fixed;
        }

        .box {
            background: rgba(255, 255, 255, 0.08);
            padding: 1rem;
            border-radius: 20px;
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(8px);
            max-width: 1400px;
            margin: 1rem auto;
            color: #fff;
            animation: fadeIn .9s ease-out;
        }

        .box h2 {
            font-size: clamp(1rem, 3vw, 1.25rem);
            margin-bottom: 0.75rem;
            font-weight: 700;
            text-align: center;
        }

        #contenedorListasCompartidas {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .lista-item {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 15px;
            padding: 0.75rem;
            backdrop-filter: blur(5px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 0.5rem;
            height: 100%;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .lista-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.35);
            background: rgba(255, 255, 255, 0.12);
        }

        .lista-item h3 {
            font-size: clamp(0.9rem, 2.5vw, 1rem);
            font-weight: 600;
            margin-bottom: .3rem;
        }

        .lista-item p {
            color: rgba(255,255,255,0.85);
            margin: 0.2rem 0;
            font-size: clamp(0.8rem, 2vw, 0.9rem);
        }

        .lista-detalles span {
            display: inline-block;
            margin-right: 0.5rem;
            font-size: clamp(0.7rem, 1.8vw, 0.8rem);
            color: rgba(255,255,255,0.7);
            white-space: nowrap;
        }

        .ver-lista-btn {
            background: linear-gradient(90deg, #ffde59, #ffb84d);
            color: #1f1f1f;
            font-weight: 700;
            border: none;
            padding: .4rem 0.8rem;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            font-size: clamp(0.75rem, 2vw, 0.875rem);
            display: inline-block;
            text-align: center;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @media (max-width: 1200px) {
            #contenedorListasCompartidas {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            #contenedorListasCompartidas {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width:560px) {
            .box {
                padding: 0.75rem;
                margin: 0.5rem;
            }
            
            .box h2 {
                font-size: 1rem;
            }

            .lista-item {
                padding: 0.6rem;
            }
            
            .lista-item h3 {
                font-size: 0.9rem;
            }

            .lista-detalles {
                display: flex;
                flex-wrap: wrap;
                gap: 0.25rem;
            }

            .lista-detalles span {
                font-size: 0.7rem;
                margin-right: 0.4rem;
            }

            .ver-lista-btn {
                margin-top: 0.6rem;
                width: 100%;
                text-align: center;
                padding: 0.5rem;
                font-size: 0.8rem;
            }
        }
    </style>
    <x-slot name="navbar">
        <a href="{{ route('dashboard') }}"
               class="px-4 py-2 bg-white/10 text-white font-semibold rounded-2xl backdrop-blur-xl hover:bg-white/20 transition-colors flex items-center space-x-2">
                🏠 <span>Inicio</span>
        </a>
    </x-slot>

    <div class="box">
        <h2>📥 Listas Compartidas Conmigo</h2>
        <div id="contenedorListasCompartidas">
            <p class="text-center text-gray-300">Cargando...</p>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/api/listas/compartidas')
            .then(respuesta => respuesta.json())
            .then(listas => {
                const contenedor = document.getElementById('contenedorListasCompartidas');
                
                if (listas.length === 0) {
                    contenedor.innerHTML = `<p class="text-center text-gray-300">No tienes listas compartidas</p>`;
                    return;
                }

                contenedor.innerHTML = listas.map(lista => `
                    <a class="lista-item" href="/listas/${lista.id}?from=compartidas" style="text-decoration: none; color: inherit;">
                        <div>
                            <h3>${lista.nombre}</h3>
                            <p>${lista.descripcion || 'Sin descripción'}</p>
                            <div class="lista-detalles">
                                <span>👤 De: ${lista.propietario.nombre}</span>
                                <span>📝 Rol: ${lista.rol === 'editor' ? 'Editor' : 'Lector'}</span>
                                <span>📅 ${new Date(lista.fecha_compartida).toLocaleDateString()}</span>
                            </div>
                        </div>
                    </a>
                `).join('');
            })
            .catch(error => {
                document.getElementById('contenedorListasCompartidas').innerHTML = `
                    <p class="text-red-500">Error al cargar las listas: ${error.message}</p>
                `;
            });
    });
    </script>
</x-guest-layout>

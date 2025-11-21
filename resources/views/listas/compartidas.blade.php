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
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(8px);
            max-width: 720px;
            margin: 2rem auto;
            color: #fff;
            animation: fadeIn .9s ease-out;
        }

        .box h2 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
            text-align: center;
        }

        .lista-item {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 15px;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            backdrop-filter: blur(5px);
            display: flex;
            justify-content: space-between;
            align-items: start;
            flex-wrap: wrap;
        }

        .lista-item h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: .4rem;
        }

        .lista-item p {
            color: rgba(255,255,255,0.85);
            margin: 0.2rem 0;
        }

        .lista-detalles span {
            display: inline-block;
            margin-right: 1rem;
            font-size: .9rem;
            color: rgba(255,255,255,0.7);
        }

        .ver-lista-btn {
            background: linear-gradient(90deg, #ffde59, #ffb84d);
            color: #1f1f1f;
            font-weight: 700;
            border: none;
            padding: .5rem 1rem;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
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

        @media (max-width:560px) {
            .box {
                padding: 1.4rem;
                margin: 1rem;
            }

            .lista-item {
                flex-direction: column;
            }

            .ver-lista-btn {
                margin-top: 0.8rem;
                width: 100%;
                text-align: center;
            }
        }
    </style>
    <x-slot name="navbar">
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 bg-white/10 text-white font-semibold rounded-lg hover:bg-white/20 transition flex items-center space-x-2">
                🏠 Inicio
            </a>
        </div>
    </x-slot>

    <div class="box">
        <h2>📥 Listas Compartidas Conmigo</h2>
        <div id="contenedorListasCompartidas">
            <p class="text-center text-gray-300">Cargando...</p>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/listas/compartidas')
            .then(respuesta => respuesta.json())
            .then(listas => {
                const contenedor = document.getElementById('contenedorListasCompartidas');
                
                if (listas.length === 0) {
                    contenedor.innerHTML = `<p class="text-center text-gray-300">No tienes listas compartidas</p>`;
                    return;
                }

                contenedor.innerHTML = listas.map(lista => `
                    <div class="lista-item">
                        <div>
                            <h3>${lista.nombre}</h3>
                            <p>${lista.descripcion || 'Sin descripción'}</p>
                            <div class="lista-detalles">
                                <span>👤 De: ${lista.propietario.nombre}</span>
                                <span>📝 Rol: ${lista.rol === 'editor' ? 'Editor' : 'Lector'}</span>
                                <span>📅 ${new Date(lista.fecha_compartida).toLocaleDateString()}</span>
                            </div>
                        </div>
                        <a href="/listas/${lista.id}" class="ver-lista-btn">Ver Lista</a>
                    </div>
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

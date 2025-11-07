// Variables globales
let idListaActual = null;
let temporizadorBusqueda = null;

/**
 * Abrir modal de compartir
 */
function abrirModalCompartir(idLista) {
    idListaActual = idLista;
    $('#modalCompartir').removeClass('hidden');
    $('#buscarUsuario').focus();
}

/**
 * Cerrar modal de compartir
 */
function cerrarModalCompartir() {
    $('#modalCompartir').addClass('hidden');
    $('#formularioCompartir')[0].reset();
    $('#resultadosBusqueda').addClass('hidden');
    $('#mostrarUsuarioSeleccionado').addClass('hidden');
    $('#mensajeCompartir').addClass('hidden');
    $('#idUsuarioSeleccionado').val('');
}

/**
 * Buscar usuarios mientras se escribe
 */
$(document).ready(function() {
    $('#buscarUsuario').on('input', function(e) {
        const busqueda = $(this).val().trim();
        
        // Limpiar timeout anterior
        clearTimeout(temporizadorBusqueda);
        
        if (busqueda.length < 2) {
            $('#resultadosBusqueda').addClass('hidden');
            return;
        }
        
        // Esperar 300ms antes de buscar (debounce)
        temporizadorBusqueda = setTimeout(() => {
            buscarUsuarios(busqueda);
        }, 300);
    });
});

/**
 * Realizar búsqueda de usuarios
 */
function buscarUsuarios(busqueda) {
    fetch(`/usuarios/buscar?q=${encodeURIComponent(busqueda)}`)
        .then(respuesta => respuesta.json())
        .then(usuarios => {
            mostrarResultadosBusqueda(usuarios);
        })
        .catch(error => {
            console.error('Error buscando usuarios:', error);
        });
}

/**
 * Mostrar resultados de búsqueda
 */
function mostrarResultadosBusqueda(usuarios) {
    const $divResultados = $('#resultadosBusqueda');
    const $contenedorResultados = $divResultados.find('div').first();
    
    if (usuarios.length === 0) {
        $contenedorResultados.html('<p class="p-3 text-sm text-gray-500">No se encontraron usuarios</p>');
        $divResultados.removeClass('hidden');
        return;
    }
    
    const html = usuarios.map(usuario => `
        <div class="p-3 hover:bg-gray-50 cursor-pointer border-b last:border-b-0" 
             onclick="seleccionarUsuario(${usuario.id}, '${usuario.name}', '${usuario.email}')">
            <div class="font-medium text-gray-800">${usuario.name}</div>
            <div class="text-sm text-gray-500">${usuario.email}</div>
        </div>
    `).join('');
    
    $contenedorResultados.html(html);
    $divResultados.removeClass('hidden');
}

/**
 * Seleccionar un usuario
 */
function seleccionarUsuario(idUsuario, nombreUsuario, emailUsuario) {
    $('#idUsuarioSeleccionado').val(idUsuario);
    $('#nombreUsuarioSeleccionado').text(`${nombreUsuario} (${emailUsuario})`);
    $('#mostrarUsuarioSeleccionado').removeClass('hidden');
    $('#resultadosBusqueda').addClass('hidden');
    $('#buscarUsuario').val('');
}

/**
 * Limpiar selección de usuario
 */
function limpiarSeleccionUsuario() {
    $('#idUsuarioSeleccionado').val('');
    $('#mostrarUsuarioSeleccionado').addClass('hidden');
    $('#buscarUsuario').focus();
}

/**
 * Enviar formulario de compartir
 */
$(document).ready(function() {
    $('#formularioCompartir').on('submit', function(e) {
        e.preventDefault();
        
        const idUsuario = $('#idUsuarioSeleccionado').val();
        const rol = $('input[name="rol"]:checked').val();
        
        if (!idUsuario) {
            mostrarMensaje('error', 'Por favor selecciona un usuario');
            return;
        }
        
        // Obtener el email del usuario seleccionado
        const textoUsuario = $('#nombreUsuarioSeleccionado').text();
        const identificadorUsuario = textoUsuario.match(/\((.*?)\)/)[1]; // Extraer email
        
        // Deshabilitar botón
        const $botonCompartir = $('#botonCompartir');
        $botonCompartir.prop('disabled', true).text('Compartiendo...');
        
        // Enviar petición con fetch
        fetch(`/listas/${idListaActual}/compartir`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                identificador_usuario: identificadorUsuario,
                rol: rol
            })
        })
        .then(respuesta => respuesta.json())
        .then(datos => {
            if (datos.exito) {
                mostrarMensaje('exito', datos.mensaje);
                setTimeout(() => {
                    cerrarModalCompartir();
                    cargarUsuariosCompartidos(idListaActual);
                }, 1500);
            } else {
                mostrarMensaje('error', datos.mensaje);
                $botonCompartir.prop('disabled', false).text('Compartir');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarMensaje('error', 'Error al compartir la lista');
            $botonCompartir.prop('disabled', false).text('Compartir');
        });
    });
});

/**
 * Mostrar mensaje en el modal
 */
function mostrarMensaje(tipo, mensaje) {
    const $divMensaje = $('#mensajeCompartir');
    const colorFondo = tipo === 'exito' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
    
    $divMensaje.attr('class', `p-3 rounded-md ${colorFondo}`);
    $divMensaje.text(mensaje);
    $divMensaje.removeClass('hidden');
}

/**
 * Cargar usuarios con acceso a la lista
 */
function cargarUsuariosCompartidos(idLista) {
    const $contenedorLista = $('#listaUsuariosCompartidos');
    
    fetch(`/listas/${idLista}/usuarios-compartidos`)
        .then(respuesta => respuesta.json())
        .then(usuarios => {
            if (usuarios.length === 0) {
                $contenedorLista.html('<p class="text-gray-500 text-sm">No hay usuarios con acceso a esta lista</p>');
                return;
            }
            
            const plantilla = document.getElementById('plantillaUsuarioCompartido');
            $contenedorLista.empty();
            
            usuarios.forEach(usuario => {
                const clon = plantilla.content.cloneNode(true);
                
                // Iniciales
                const iniciales = usuario.nombre.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                $(clon).find('.iniciales-usuario').text(iniciales);
                
                // Datos
                $(clon).find('.nombre-usuario').text(usuario.nombre);
                $(clon).find('.email-usuario').text(usuario.email);
                
                // Select de rol
                const $selectorRol = $(clon).find('.selector-rol');
                $selectorRol.val(usuario.rol);
                $selectorRol.on('change', function() {
                    actualizarRolUsuario(idLista, usuario.id, $(this).val());
                });
                
                // Botón revocar
                const $botonRevocar = $(clon).find('.boton-revocar');
                $botonRevocar.on('click', function() {
                    revocarAcceso(idLista, usuario.id, usuario.nombre);
                });
                
                $contenedorLista.append(clon);
            });
        })
        .catch(error => {
            console.error('Error cargando usuarios:', error);
            $contenedorLista.html('<p class="text-red-500 text-sm">Error al cargar usuarios</p>');
        });
}

/**
 * Actualizar rol de usuario
 */
function actualizarRolUsuario(idLista, idUsuario, nuevoRol) {
    fetch(`/listas/${idLista}/compartir/${idUsuario}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify({ rol: nuevoRol })
    })
    .then(respuesta => respuesta.json())
    .then(datos => {
        if (datos.exito) {
            console.log('Rol actualizado');
        } else {
            alert(datos.mensaje);
            cargarUsuariosCompartidos(idLista);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar el rol');
        cargarUsuariosCompartidos(idLista);
    });
}

/**
 * Revocar acceso a usuario
 */
function revocarAcceso(idLista, idUsuario, nombreUsuario) {
    if (!confirm(`¿Estás seguro de revocar el acceso a ${nombreUsuario}?`)) {
        return;
    }
    
    fetch(`/listas/${idLista}/compartir/${idUsuario}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    .then(respuesta => respuesta.json())
    .then(datos => {
        if (datos.exito) {
            cargarUsuariosCompartidos(idLista);
        } else {
            alert(datos.mensaje);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al revocar el acceso');
    });
}
function editarProducto(id) {
    // Obtener los datos del producto
    const productoDiv = document.querySelector(`[data-producto-id="${id}"]`);
    const nombre = productoDiv.querySelector('.nombre').textContent;
    const cantidad = productoDiv.querySelector('.cantidad').textContent;
    const categoriaId = productoDiv.querySelector('.categoria-id').value;
    
    // Rellenar el formulario de edición
    document.getElementById('edit-name').value = nombre;
    document.getElementById('edit-cantidad').value = cantidad;
    document.getElementById('edit-categoria').value = categoriaId;
    document.getElementById('edit-form').action = document.getElementById('edit-form').action.replace('__ID__', id);
    
    // Mostrar el modal de edición
    document.getElementById('modal-editar-producto').classList.remove('hidden');
}
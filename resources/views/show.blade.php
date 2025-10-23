@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h2>{{ $lista->name }}</h2>
        <a href="{{ route('home') }}" class="btn btn-secondary">Volver</a>
    </div>

    @if ($isOwner)
    <div class="mb-3">
        <a href="{{ route('listas.edit', $lista) }}" class="btn btn-warning">Editar lista</a>
        <form action="{{ route('listas.destroy', $lista) }}" method="POST" style="display:inline">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" onclick="return confirm('¿Eliminar lista?')">Eliminar lista</button>
        </form>
    </div>
    @endif

    <h3>Categorías</h3>
    <div class="row">
        @foreach ($lista->categorias as $categoria)
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5>{{ $categoria->name }}</h5>
                    <a href="{{ route('listas.categorias.show', [$lista, $categoria]) }}" class="btn btn-sm btn-primary">Ver categoría</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <a href="{{ route('listas.categorias.create', $lista) }}" class="btn btn-primary mb-3">Añadir categoría</a>

    <h3>Productos en esta lista</h3>
    <div class="row">
        @foreach ($lista->categorias as $categoria)
            @foreach ($categoria->productos as $producto)
            <div class="col-md-4">
                <div class="card mb-3 @if($producto->completed) border-success @endif">
                    <div class="card-body">
                        <h5 class="@if($producto->completed) text-decoration-line-through @endif">{{ $producto->name }}</h5>
                        <p>Cantidad: {{ $producto->cantidad }}</p>
                        @if ($producto->imagen)
                          <img src="{{ asset('storage/' . $producto->imagen) }}" alt="imagen" class="img-fluid mb-2">
                        @endif
                        @if (! $producto->completed)
                        <form action="{{ route('productos.complete', $producto) }}" method="POST" style="display:inline">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm btn-success">Marcar completado</button>
                        </form>
                        @endif
                        <a href="{{ route('categorias.productos.edit', [$categoria, $producto]) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('categorias.productos.destroy', [$categoria, $producto]) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar producto?')">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        @endforeach
    </div>

    {{-- Form para compartir la lista (solo si eres dueño) --}}
    @if ($isOwner)
    <div class="mt-4">
        <h4>Compartir esta lista</h4>
        <form action="{{ route('listas.share', $lista) }}" method="POST">
            @csrf
            <div class="mb-2">
                <input type="email" name="user_email" class="form-control" placeholder="Correo del usuario" required>
            </div>
            <button class="btn btn-info">Compartir</button>
        </form>
    </div>
    @endif

</div>
@endsection

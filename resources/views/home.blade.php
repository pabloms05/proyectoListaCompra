@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Mis Listas</h2>
    <a href="{{ route('listas.create') }}" class="btn btn-primary">Crear nueva lista</a>
    <div class="row">
        @foreach ($listasPropias as $lista)
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $lista->name }}</h5>
                    <p class="card-text">{{ $lista->description }}</p>
                    <a href="{{ route('listas.show', $lista) }}" class="btn btn-sm btn-success">Ver</a>
                    <a href="{{ route('listas.edit', $lista) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('listas.destroy', $lista) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Borrar esta lista?')">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <h2>Listas Compartidas</h2>
    <div class="row">
        @foreach ($listasCompartidas as $lista)
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $lista->name }}</h5>
                    <p class="card-text">{{ $lista->description }}</p>
                    <a href="{{ route('listas.show', $lista) }}" class="btn btn-sm btn-success">Ver</a>
                    <!-- No muestras editar o eliminar si no eres owner, o lo haces con condiciones -->
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

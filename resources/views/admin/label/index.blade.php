@extends('layouts.basic')

@section('title', 'Admin - Categorías')
@section('bodyclass', 'crud')

@section('content')
<div class="crud">
    <h1>Categorías</h1>
    <div class="crud-header">
        <button class="font-medium" onclick="openNewForm()">Nueva categoría</button>
        <div class="crud-search">
            <input type="text" name="src" id="src" placeholder="Buscar categoría...">
        </div>
    </div>

    <div class="crud-container">
        <div id="labelContainer" class="crud-content">

        </div>
    </div>
    <script src="{{ asset('js/label.js') }}"></script>
    <script>
        src.addEventListener("input", () => {
            showLabels();
        });
        window.onload = showLabels();
    </script>
    <div id="no-mobiles">
        <span class="font-bold">Sólo puedes entrar en este apartado si tu dispositivo tiene la pantalla en horizontal.</span>
    </div>
</div>
@endsection
@extends('layouts.basic')

@section('title', 'Admin - Marcadores')
@section('bodyclass', 'crud')

@section('headers')
<meta name="csrf-token" content="{{csrf_token()}}">
@endsection

@section('content')
<div class="crud">
    <h1>Marcadores</h1>
    <div class="crud-header">
        <button class="font-medium" onclick="openNewForm()">Nuevo usuario</button>
        <div class="crud-search">
            <input type="text" name="src" id="src" placeholder="Buscar usuario...">
        </div>
    </div>

    <div class="crud-back-button">
        <div onclick="window.location.href = '/index'" class="back-arrow-img">
            <img class="reload-arrow" src="{{ asset('img/exit_icon.png') }}" alt="gincanas">
        </div>
    </div>

    <div class="crud-marks-button">
        <div onclick="window.location.href = '/admin/point'" class="back-arrow-img">
            <img class="reload-arrow" src="{{ asset('img/logo_sm.png') }}" alt="gincanas">
        </div>
    </div>

    <div class="crud-labels-button">
        <div onclick="window.location.href = '/admin/label'" class="back-arrow-img">
            <img class="reload-arrow" src="{{ asset('img/label_icon.png') }}" alt="gincanas">
        </div>
    </div>

    <div class="crud-users-button">
        <div onclick="window.location.href = '/admin/user'" class="back-arrow-img">
            <img class="reload-arrow" src="{{ asset('img/user_icon.png') }}" alt="gincanas">
        </div>
    </div>

    <div class="crud-container">
        <div id="userContainer" class="crud-content">
            <div class="colLista">
                <input type="text" name="src" id="src" placeholder="Buscar puntos">
                <div id="pointList"></div>
            </div>
            <div class="colMapa">
                <div id="map"></div>
            </div>
        </div>
    </div>
    <script src="{{asset('js/points.js')}}"></script>
    <div id="no-mobiles">
        <span class="font-bold">Sólo puedes entrar en este apartado si tu dispositivo tiene la pantalla en horizontal.</span>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/app.js') }}"></script>
@endsection






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('leaflet/leaflet.css')}}" />
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

    <script src="{{asset('leaflet/leaflet.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Admin - Puntos</title>
</head>
<body>
    
</body>
</html>
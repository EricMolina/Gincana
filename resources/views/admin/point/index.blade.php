<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('leaflet/leaflet.css')}}" />
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/pointMap.css')}}">
    <script src="{{asset('leaflet/leaflet.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Admin - Puntos</title>
</head>
<body>
    <h1>Puntos</h1>
    <div class="linea">
        <div class="colLista">
            <input type="text" name="src" id="src" placeholder="Buscar puntos">
            <div id="pointList"></div>
        </div>
        <div class="colMapa">
            <div id="map"></div>
        </div>
    </div>
    <script src="{{asset('js/points.js')}}"></script>
</body>
</html>
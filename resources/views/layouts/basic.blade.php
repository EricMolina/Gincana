@php
@endphp
@auth
    @php
    //Hacer cosas si el usuario está autenticado
    @endphp
@endauth

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>
    <title>@yield('title')</title>
</head>
<body>
    <div class="background">
    </div>
    <header>
        <div class="header-searchbar">
            
        </div>
        <div class="header-labels">

        </div>
    </header>
    <div class="profile-container">
        @php echo $links; @endphp
        <p onclick="MyProfile()">Mi perfil</p>
        <p onclick="window.location.href = '{{ route('logout'); }}'">Cerrar sesión</p>
    </div>
    <div class="content">
        <div class="container @yield('tipo')">
            <div class="title-container">
                <span>@yield('titulo')</span>
            </div>
            <div id="layout-cabeceras">
                @yield('cabeceras')
            </div>
            <div id="loading-container">
                <img class="loading-circle" src="{{ asset('img/loading-circle.png') }}" alt="Loading...">
            </div>
            <div id="layout-contenido">
                @yield('contenido')
            </div>
        </div>
    </div>
    @yield('scripts')
</body>
</html>

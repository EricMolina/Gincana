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
    <script src="{{ asset('js/map.js') }}"></script>
    <title>@yield('title')</title>
</head>
<body>
    <div id="background">
    </div>
    <header>
        <div class="header-searchbar">
            <div class="icon">
                <img src="{{ asset('img/logo_sm.png') }}" alt="logo">
            </div>
            <div class="header-search-input">
                <input class="font-light" type="text" name="search" id="search" placeholder="Buscar aquí">
            </div>
            <div class="icon">
                <img src="{{ asset('img/default_user.png') }}" alt="">
            </div>
        </div>
        <div class="header-labels">
            <div class="header-label">
                <span class="font-light">Label ejemplo</span>
            </div>
            <div class="header-label">
                <span class="font-light">Label ejemplo</span>
            </div>
            <div class="header-user-label">
                <span class="font-light">Mi label ejemplo</span>
            </div>
        </div>
    </header>
    <footer>
        <div class="footer-buttons">
            <div class="footer-small-buttons">
                <div onclick="zoomIn()" class="footer-small-button">
                    <img class="img-icon" src="{{ asset('img/zoom_in_icon.png') }}" alt="zoom-in">
                </div>
                <div onclick="zoomOut()" class="footer-small-button">
                    <img class="img-icon" src="{{ asset('img/zoom_out_icon.png') }}" alt="zoom-out">
                </div>
            </div>
            <div class="footer-big-buttons">
                <div class="footer-big-button">
                    <img class="img-icon" src="{{ asset('img/new_gincana_icon.png') }}" alt="create-gincana">
                </div>
                <div class="footer-big-button">
                    <img class="img-icon" src="{{ asset('img/reload_icon.png') }}" alt="">
                </div>
                <div class="footer-big-button">
                    <img class="img-icon" src="{{ asset('img/location_icon.png') }}" alt="">
                </div>
            </div>
        </div>
        <div class="footer-items">
            <div class="footer-item">
                <div class="footer-item-img">
                    <img class="img-icon" src="{{ asset('img/icon_map_selected.png') }}" alt="ubicaciones">
                </div>
                <span class="font-medium footer-item-text">Ubicaciones</span>
            </div>
            <div class="footer-item">
                <div class="footer-item-img footer-item-selected">
                    <img class="img-icon" src="{{ asset('img/icon_gincanas_selected.png') }}" alt="gincanas">
                </div>
                <span class="font-medium footer-item-text">Gincanas</span>
            </div>
            <div class="footer-item">
                <div class="footer-item-img">
                    <img class="img-icon" src="{{ asset('img/icon_activity_selected.png') }}" alt="mi-actividad">
                </div>
                <span class="font-medium footer-item-text">Mi actividad</span>
            </div>
        </div>
    </footer>
    <div class="content">
    </div>
    @yield('scripts')
</body>
</html>

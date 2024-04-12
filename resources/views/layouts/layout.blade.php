@php
@endphp
@auth
    @php
        //Hacer cosas si el usuario está autenticado
        $user = Auth::user();
        $userImage = '../img/default_user.png';
        if ($user->img != null) {
            $userImage = '../img/users/'.$user->img;
        }
        //blade php console log $user
    @endphp
@endauth

@if(!Session::get('current_activity'))
<script>var inActivity = false;</script>
@else
<script>var inActivity = true;</script>
@endif

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
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <script src="{{ asset('js/map.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
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
                <input id="bar-search-input" class="font-light" type="text" name="search" id="search" placeholder="Buscar aquí">
            </div>
            <div onclick="openUserProfile(true)" class="icon user-icon">
                <img id="search-bar-profile-img" src="{{ $userImage }}" alt="">
            </div>
        </div>
        <div id="header-content-labels" class="header-labels">
        </div>
        <div id="bar-search-container">
            <div id="bar-search-content">
            </div>
        </div>
    </header>
    <footer>
        <div class="footer-buttons">
            <div class="footer-small-buttons">
                <div id="remove-route-button" style="display: none;" onclick="removeTraceRoute()" class="footer-small-button">
                    <img class="img-icon" src="{{ asset('img/icon_route_cancel.png') }}" alt="remove-route">
                </div>
                <div onclick="zoomIn()" class="footer-small-button">
                    <img class="img-icon" src="{{ asset('img/zoom_in_icon.png') }}" alt="zoom-in">
                </div>
                <div onclick="zoomOut()" class="footer-small-button">
                    <img class="img-icon" src="{{ asset('img/zoom_out_icon.png') }}" alt="zoom-out">
                </div>
            </div>
            <div class="footer-big-buttons">
                <div id="current-activity-button" class="footer-big-button" style="display: none">
                    <img class="img-icon" src="{{ asset('img/clue_icon.png') }}" alt="clue-icon">
                </div>
                <div onclick="openGincanaModal()" id="create-gincana-button" class="footer-big-button" style="display: none">
                    <img class="img-icon" src="{{ asset('img/new_gincana_icon.png') }}" alt="create-gincana">
                </div>
                <div onclick="loadPointers()" class="footer-big-button">
                    <img class="img-icon" src="{{ asset('img/reload_icon.png') }}" alt="">
                </div>
                <div onclick="centerMapOnUser()" class="footer-big-button">
                    <img class="img-icon" src="{{ asset('img/location_icon.png') }}" alt="">
                </div>
            </div>
        </div>
        <div class="footer-items">
            <div class="footer-item">
                <div id="tab1" onclick="changeTab(1)" class="footer-item-img footer-item-selected">
                    <img class="img-icon" src="{{ asset('img/icon_map_selected.png') }}" alt="ubicaciones">
                </div>
                <span class="font-medium footer-item-text">Ubicaciones</span>
            </div>
            <div class="footer-item">
                <div id="tab2" onclick="changeTab(2)" class="footer-item-img">
                    <img class="img-icon" src="{{ asset('img/icon_gincanas_selected.png') }}" alt="gincanas">
                </div>
                <span class="font-medium footer-item-text">Gincanas</span>
            </div>
            <div class="footer-item">
                <div id="tab3" onclick="changeTab(3)" class="footer-item-img footer-item-disabled">
                    <img class="img-icon" src="{{ asset('img/icon_activity_selected.png') }}" alt="mi-actividad">
                </div>
                <span class="font-medium footer-item-text">Mi actividad</span>
            </div>
        </div>
    </footer>
    <div class="content">
    </div>
    <div id="user-profile-container" style="display: none">
        <div class="user-profile">
            <div onclick="selectUserImage()" class="user-profile-principal-container">
                <div onclick="changeUserPhoto(id)" class="user-profile-principal-img-container">
                    <img id="user-profile-image" class="user-profile-principal-img-container-img" src="{{ $userImage }}"
                        alt="user-photo">
                    <img class="user-profile-principal-img-container-change" src="{{ asset('img/photo_icon.png') }}"
                        alt="change">
                </div>
                <div class="user-profile-name-container">
                    <span class="font-bold">{{ $user->name }}</span>
                </div>
                <div class="user-profile-email-container">
                    <span class="font-medium">{{ $user->email }}</span>
                </div>
            </div>
            <div id="user-profile-labels" class="profile-labels">
            </div>
            <div class="profile-buttons">
                <div class="profile-button">
                    <div onclick="createUserLabel({{ $user->id }})" class="footer-item-img profile-button-label">
                        <img class="img-icon" src="{{ asset('img/label_icon.png') }}" alt="ubicaciones">
                    </div>
                    <span class="font-medium footer-item-text">Crear etiqueta</span>
                </div>
                <div class="profile-button">
                    <div onclick="window.location.href = '/logout'" class="footer-item-img profile-button-close">
                        <img class="img-icon" src="{{ asset('img/exit_icon.png') }}" alt="gincanas">
                    </div>
                    <span class="font-medium footer-item-text">Cerrar sesión</span>
                </div>
            </div>
            <div class="profile-close-container">
                <div class="back-arrow-container">
                    <div onclick="openUserProfile(false)" class="back-arrow-img">
                        <img class="back-arrow" src="{{ asset('img/back_arrow.png') }}" alt="gincanas">
                    </div>
                </div>
                @if ($user->role == 'admin')
                    <div class="profile-crud-button">
                        <div onclick="window.location.href = '/admin/point'" class="back-arrow-img">
                            <img class="back-arrow" src="{{ asset('img/crud_icon.png') }}" alt="gincanas">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div id="bottom-container" style="display: none">
        <div class="bottom-content">
            <div class="bottom-close-container">
                <div class="back-arrow-container">
                    <div onclick="openBottomContainer(false)" class="back-arrow-img">
                        <img class="back-arrow" src="{{ asset('img/back_arrow.png') }}" alt="gincanas">
                    </div>
                </div>
                <div class="bottom-reload-button">
                    <div id="reload-button" class="back-arrow-img">
                        <img class="reload-arrow" src="{{ asset('img/reload_icon.png') }}" alt="gincanas">
                    </div>
                </div>

                <div id="play-activity-button" class="bottom-play-button" style="display: none">
                    <div class="back-arrow-img">
                        <img class="play-arrow" src="{{ asset('img/icon_play.png') }}" alt="gincanas">
                    </div>
                </div>

            </div>
            <div id="bottom-container-content">

            </div>
        </div>
    </div>
    <div id="load">
        <img id="load_img" src="{{ asset('img/loading_circle.png') }}" alt="">
    </div>
    @yield('scripts')
    <script>
        setUserPointerName('{{ $user->name }}');
        UserId({{ $user->id}});
        if (!inActivity) disableTab(3);
    </script>
</body>

</html>

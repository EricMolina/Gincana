@php
@endphp
@auth
    @php
    //Hacer cosas si el usuario está autenticado
    $user = Auth::user();
    $userImage = "../img/default_user.png";
    if ($user->image != null) {
        $userImage = $user->image;
    }
    //blade php console log $user
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
                <input class="font-light" type="text" name="search" id="search" placeholder="Buscar aquí">
            </div>
            <div onclick="openUserProfile(true)" class="icon user-icon">
                <img src="{{$userImage}}" alt="">
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
            <div class="user-profile-principal-container">
                <div onclick="changeUserPhoto(id)" class="user-profile-principal-img-container">
                    <img class="user-profile-principal-img-container-img" src="{{$userImage}}" alt="user-photo">
                    <img class="user-profile-principal-img-container-change" src="{{ asset('img/photo_icon.png') }}" alt="change">
                </div>
                <div class="user-profile-name-container">
                    <span class="font-bold">{{$user->name}}</span>
                </div>
                <div class="user-profile-email-container">
                    <span class="font-medium">{{$user->email}}</span>
                </div>
            </div>
            <div class="profile-labels">
                <div class="profile-label">
                    <span class="font-light">Mi label ejemplo</span>
                </div>
                <div class="profile-label">
                    <span class="font-light">Mi label ejemplo</span>
                </div>
                <div class="profile-label">
                    <span class="font-light">Mi label ejemplo</span>
                </div>
            </div>
            <div class="profile-buttons">
                <div class="profile-button">
                    <div onclick="createUserLabel(user_id)" class="footer-item-img profile-button-label">
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
            <span class="font-light-italic">Gincanas en las que he participado: 10</span>
            <br>
            <span class="font-light-italic">Gincanas en las que he ganado: 2</span>
            <div class="profile-close-container">
                <div class="back-arrow-container">
                    <div onclick="openUserProfile(false)" class="back-arrow-img">
                        <img class="back-arrow" src="{{ asset('img/back_arrow.png') }}" alt="gincanas">
                    </div>
                </div>
                <div class="profile-crud-button">
                    <div onclick="window.location.href = '/crud/points'" class="back-arrow-img">
                        <img class="back-arrow" src="{{ asset('img/crud_icon.png') }}" alt="gincanas">
                    </div>
                </div>
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
                        <img class="back-arrow" src="{{ asset('img/reload_icon.png') }}" alt="gincanas">
                    </div>
                </div>
            </div>
            <div id="bottom-container-content">


                {{-- <div class="bottom-gincana-selected">
                    <h1 class="font-bold bottom-gincana-selected-title">Nombre de la gincana seleccionada</h1>
                    <p class="font-medium bottom-gincana-selected-desc">Desc</p>
                    <span class="bottom-gincana-selected-creator">Creada por: Usuario</span>
                    <span class="bottom-gincana-selected-checkpoints">Puntos de control: 18</span>
                    <h1 class="font-bold bottom-gincana-selected-title">Sesiones activas</h1>
                    <div class="bottom-gincana-selected-session">
                        <div class="bottom-gincana-selected-session-container">
                            <p class="bottom-gincana-selected-session-container-title">Sesión de ejemplo</p>
                            <p class="bottom-gincana-selected-session-container-creator">Creador: Usuario de ejemplo</p>
                        </div>
                        <div class="bottom-gincana-selected-session-join">
                            <img src="{{asset ('img/arrow_up_icon.png') }}" alt="ver">
                        </div>
                    </div>
                    <div class="bottom-gincana-selected-session">
                        <div class="bottom-gincana-selected-session-container">
                            <p class="bottom-gincana-selected-session-container-title">Sesión de ejemplo</p>
                            <p class="bottom-gincana-selected-session-container-creator">Creador: Usuario de ejemplo</p>
                        </div>
                        <div onclick="checkSessionGroups(session_id)" class="bottom-gincana-selected-session-join">
                            <img src="{{asset ('img/arrow_up_icon.png') }}" alt="ver">
                        </div>
                    </div>
                </div> --}}

                {{-- <div class="bottom-gincana-session">
                    <h1 class="font-bold bottom-gincana-session-title">Nombre de la gincana seleccionada</h1>
                    <p class="font-medium-italic bottom-gincana-session-name">Nombre de la sesión</p>
                    <div class="bottom-gincana-session-groups-title-container">
                        <span class="font-medium bottom-gincana-session-groups-title">Grupos de la sesión</span>
                        <div class="bottom-gincana-session-groups-create">
                            <img src="{{ asset('img/create_group_icon.png')}}" alt="create">
                        </div>
                    </div>
                    
                    <div class="bottom-gincana-session-group">
                        <span class="font-medium bottom-gincana-session-group-title">Nombre del grupo</span>

                        <div class="font-medium bottom-gincana-session-group-user"><span>Usuario de ejemplo</span></div>
                        <div class="font-medium bottom-gincana-session-group-user"><span>Usuario de ejemplo</span></div>
                        <div class="font-medium bottom-gincana-session-group-user"><span>Usuario de ejemplo</span></div>

                        <div class="bottom-gincana-session-group-join-container">
                            <div class="bottom-gincana-session-group-join">
                                <img src="{{ asset('img/join_icon.png')}}" alt="join">
                            </div>
                        </div>
                        
                    </div>

                </div> --}}

                
                <div class="bottom-gincana-mysession">
                    <h1 class="font-bold bottom-gincana-session-title">Nombre de la gincana seleccionada</h1>
                    <p class="font-medium-italic bottom-gincana-session-name">Nombre de la sesión</p>

                    <!-- AQUÍ VAN LAS PISTAS!!!!!!!! (cuan  do haya empezado) -->

                    <div class="bottom-gincana-mysession-clue-container">
                        <div class="bottom-gincana-mysession-clue-title">
                            <h1 class="font-medium">Pista 1</h1>
                        </div>
                        <div class="bottom-gincana-mysession-clue">
                            <span class="font-medium bottom-gincana-mysession-clue-text">Texto de la pista</span>
                        </div>
                        <span class="font-medium-italic bottom-gincana-mysession-clue-left">Miembros restantes: 3</span>
                        <br>
                        <span class="font-medium-italic bottom-gincana-mysession-clue-arrived">Han llegado: 2</span>
                    </div>

                    <!-- SE CIERRAN LAS PISTAS -->


                    <div class="bottom-gincana-session-groups-title-container">
                        <span class="font-medium bottom-gincana-mysession-groups-title">Nombre del grupo</span>
                        <div class="bottom-gincana-mysession-groups-create">
                            <img src="{{ asset('img/exit_icon.png')}}" alt="create">
                        </div>
                    </div>
                    
                    <div class="bottom-gincana-mysession-group">
                        <span class="font-medium bottom-gincana-mysession-group-title">Nombre de un usuario</span>
                    </div>

                    <div class="bottom-gincana-mysession-group">
                        <span class="font-medium bottom-gincana-mysession-group-title">Nombre de un usuario</span>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <div id="load">
        <img id="load_img" src="{{ asset('img/loading_circle.png') }}" alt="">
    </div>
    @yield('scripts')
    <script>
        setUserPointerName('{{$user->name}}');
    </script>
</body>
</html>

var map = null;
var pointers = [];
var userPointer = { name: 'Nombre de Usuario', pointer_img: '../img/me_icon.png', coord_x: 0, coord_y: 0 };
var userLayer = null;
var pointersLayer = null;
var userId = null;

var appContent;
var currentGincana;
var currentSession;


window.onload = function () {
    appContent = document.getElementById('bottom-container-content');

    var map = initMap();
    
    requestGeoLocationPermission().then(() => {
        getGeoLocation().then(position => {
            map.setView([position.lat, position.lng], 15);
        }).catch(error => {
            console.error(error);
        });
    }).catch(error => {
        console.error('Permission denied: ', error);
    });

    UpdateUserLocation();
    setInterval(UpdateUserLocation, 2000);

    changeTab(1);
    loading(false);
    document.getElementById('bar-search-input').addEventListener('input', function() {
        filterBySearchBar();
        if (document.getElementById('bar-search-input').value == '') {
            alterSearchDiv(false);
        } else {
            alterSearchDiv(true);
        }
    });
}

function UserId(id) {
    userId = id;

    if (inActivity) {
        disableTab(2);
        enableTab(3);
    } 
}

function initMap() {
    map = L.map('background', { zoomControl: false }).fitWorld();

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    map.getContainer().style.zIndex = 0;

    pointersLayer = L.layerGroup().addTo(map);
    userLayer = L.layerGroup().addTo(map);

    return map;
}

function setUserPointerName(name) {
    userPointer.name = name;
}

function getGeoLocation() {
    return new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
            reject(new Error('Geolocation is not supported by your browser'));
        } else {
            navigator.geolocation.getCurrentPosition((position) => {
                resolve({
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                });
            }, () => {
                reject(new Error('Unable to retrieve your location'));
            });
        }
    });
}

function requestGeoLocationPermission() {
    if (!navigator.permissions) {
        // La API de Permisos no está disponible en todos los navegadores.
        return Promise.reject(new Error('Permissions API is not available'));
    }

    return navigator.permissions.query({ name: 'geolocation' }).then(result => {
        if (result.state === 'granted') {
            return Promise.resolve();
        } else if (result.state === 'prompt') {
            return getGeoLocation().then(() => Promise.resolve());
        } else {
            return Promise.reject(new Error('Permission denied'));
        }
    });
}

var userMarker = null;
function UpdateUserLocation() {
    getGeoLocation().then(position => {
        if (userPointer.coord_x == position.lat && userPointer.coord_y == position.lng) {
            return;
        }
        userPointer.coord_x = position.lat;
        userPointer.coord_y = position.lng;

        var icon = L.icon({
            iconUrl: userPointer.pointer_img,
            iconSize: [96, 96],
            iconAnchor: [48, 96]
        });

        if (userMarker) {
            userMarker.setLatLng([userPointer.coord_x, userPointer.coord_y]);
        } else {
            userMarker = L.marker([userPointer.coord_x, userPointer.coord_y], { icon: icon })
                .bindTooltip(userPointer.name, {
                    permanent: true,
                    direction: 'center',
                    offset: [-80, -48],
                    className: 'my-tooltip'
                })
                .addTo(userLayer);
        }

        /* if (inActivity) {
            sendCheckpoint({
                'lat': position.lat,
                'lng': position.lng
            });
        } */

    }).catch(error => {
        console.error(error);
    });
}

function UpdateMapPointers() {
    pointersLayer.clearLayers();
    for (var i = 0; i < pointers.length; i++) {
        var icon = L.icon({
            iconUrl: pointers[i].pointer_img,
            iconSize: [48, 64],
            iconAnchor: [32, 64]
        });

        if (pointersType == 'ubicaciones') {
            var icon = L.divIcon({
                iconSize: [48, 64],
                iconAnchor: [32, 64],
                html: `<div style="position: relative; background-color: transparent; width: 100%; height: 100%; background-image: url(${pointers[i].pointer_img}); background-size: cover; background-repeat: no-repeat; filter: drop-shadow(2px 2px 0 black);">
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: #${pointers[i].pointer_col}; opacity: 1; mask-image: url(${pointers[i].pointer_img}); mask-size: cover;"></div>
                        </div>`
            });
        }
        
        var marker = L.marker([pointers[i].coord_x, pointers[i].coord_y], { icon: icon })
            .bindTooltip(pointers[i].name, {
                permanent: true,
                direction: 'center',
                offset: [-64, -32],
                className: 'my-tooltip'
            })
            .addTo(pointersLayer);

        marker.on('click', (function(actualPointer) {
            return function () { openPointer(actualPointer); };
        })(pointers[i]));
    }
    loading(false);
}

var pointersType = '';
function loadPointers(type) {
    if (type != null) pointersType = type;

    loading(true);
    if (pointersType == 'ubicaciones') { //Ubicaciones
        var formdata = new FormData();
        var csrf_token = document.querySelector("meta[name = 'csrf-token']").getAttribute('content');
        formdata.append('_token', csrf_token);
        formdata.append('labelFilters', JSON.stringify(labelFilters));
        formdata.append('userLabelFilters', JSON.stringify(userLabelFilters));
        var ajax = new XMLHttpRequest();
        ajax.open('POST', '/api/points', true);

        ajax.onload = function() {
            if (this.status >= 200 && this.status < 400) {
                var data = JSON.parse(this.response);
                console.log(data);
                pointers = [];
                for (var i = 0; i < data.length; i++) {
                    pointers.push({
                        id: data[i].id,
                        name: data[i].name,
                        pointer_img: '../img/labels/' + data[i].main_label.img,
                        pointer_col: data[i].main_label.color,
                        img: data[i].img,
                        coord_x: data[i].coord_x,
                        coord_y: data[i].coord_y,
                        address: data[i].address,
                        desc: data[i].desc,
                        labels: data[i].labels,
                        user_labels: data[i].user_labels
                    });
                }
                
                UpdateMapPointers();
            } else {
                console.error('Error al cargar los puntos');
            }
        };

        ajax.onerror = function() {
            loading(false);
            console.error('Error de red');
        };

        ajax.send(formdata);
    } else if (pointersType == 'gincanas') { //Gincanas
        fetch('/api/gincanas/')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al cargar los puntos');
                }
                return response.json();
            })
            .then(data => {
                pointers = [];
                for (var i = 0; i < data.length; i++) {
                    pointers.push({
                        id: data[i].id,
                        name: data[i].name,
                        desc: data[i].desc,
                        difficulty: data[i].difficulty,
                        coord_x: data[i].coord_x,
                        coord_y: data[i].coord_y,
                        gincana_points_count: data[i].gincana_points_count,
                        pointer_img: '../img/gincana_icon.png',
                        user: data[i].gincana_creator.name
                    });
                }
                
                UpdateMapPointers();
            })
            .catch(error => {
                loading(false);
                console.error(error);
            });
    } else {
        UpdateMapPointers();
        loading(false);
    }
    
}

function zoomIn() {
    map.zoomIn();
}

function zoomOut() {
    map.zoomOut();
}

function centerMapOnUser() {
    map.setView([userPointer.coord_x, userPointer.coord_y], 17);
}

function openPointer(pointer) {
    openBottomContainer(true);
    if (pointersType == 'ubicaciones') {
        displayMapPointer(pointer);
    } else {
        displaySessions(pointer);
    }
}

function displayMapPointer(pointer) {

    //Recoge el coord_x y coord_y de pointer y centra el mapa en esas coordenadas
    map.setView([pointer.coord_x - 0.0011, pointer.coord_y], 17);

    document.getElementById('reload-button').style.display = 'none';
    document.getElementById('play-activity-button').style.display = 'none';
    var content = `
    <div class="bottom-mark">
        <h1 class="font-bold bottom-mark-title">${pointer.name}</h1>
        <p class="font-medium-italic bottom-mark-name">${pointer.address}</p>
        <div class="bottom-mark-labels">
    `;

    for (var i = 0; i < pointer.labels.length; i++) {
        content += `
        <div class="bottom-mark-label">
            <span class="font-light">${pointer.labels[i].name}</span>
        </div>
        `;
    }

    for (var i = 0; i < pointer.user_labels.length; i++) {
        content += `
        <div onclick="removeUserLabelPoint(${pointer.user_labels[i].id}, ${pointer.id})" class="bottom-mark-user-label">
            <span class="font-light">${pointer.user_labels[i].name}</span>
        </div>
        `;
    }

    content += `
        </div>
        <div class="bottom-mark-buttons">
            <div class="profile-button">
                <div onclick="assignUserLabel(${userId}, ${pointer.id})" class="footer-item-img profile-button-label">
                    <img class="img-icon" src="../img/label_icon.png" alt="ubicaciones">
                </div>
                <span class="font-medium footer-item-text">Añadir etiqueta</span>
            </div>
            <div class="profile-button">
                <div onclick="traceRoute(${pointer.coord_x},${pointer.coord_y})" class="footer-item-img profile-button-close">
                    <img class="img-icon" src="../img/icon_route.png" alt="gincanas">
                </div>
                <span class="font-medium footer-item-text">Trazar ruta</span>
            </div>
        </div>
        <p class="font-light bottom-mark-desc">${pointer.desc}</p>
        <img src="./img/points/${pointer.img}" alt="">
    </div>
    `;

    appContent.innerHTML = content;
}

var routingControl;
function traceRoute(lat, lng) {
    removeTraceRoute();

    routingControl = L.Routing.control({
        waypoints: [
            L.latLng(userPointer.coord_x, userPointer.coord_y),
            L.latLng(lat, lng)
        ],
        routeWhileDragging: true,
        showAlternatives: false,
        lineOptions: {
            styles: [
                {color: 'blue', opacity: 1, weight: 5}
            ]
        },
        createMarker: function() { return null; },
        addWaypoints: false,
    }).addTo(map);
    openBottomContainer(false);

    if (document.getElementsByClassName('leaflet-control-container')[0])
        document.getElementsByClassName('leaflet-control-container')[0].remove();
    document.getElementById('remove-route-button').style.display = 'flex';

    var midLat = (userPointer.coord_x + lat) / 2;
    var midLng = (userPointer.coord_y + lng) / 2;
    map.setView([midLat, midLng], 14);
}

function removeTraceRoute() {
    if (routingControl) {
        map.removeControl(routingControl);
        routingControl = null;
    }
    document.getElementById('remove-route-button').style.display = 'none';
}

function alterSearchDiv(b) {
    if (b) {
        document.getElementById('bar-search-container').style.display = 'inline-block';
        document.getElementById('header-content-labels').style.display = 'none';
        filterBySearchBar();
    } else {
        document.getElementById('bar-search-container').style.display = 'none';
        document.getElementById('header-content-labels').style.display = 'inline-block';
    }
}

function filterBySearchBar() {
    var formdata = new FormData();
    var csrf_token = document.querySelector("meta[name = 'csrf-token']").getAttribute('content');
    formdata.append('_token', csrf_token);
    formdata.append('search', document.getElementById('bar-search-input').value);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', '/api/points/search', true);

    ajax.onload = function() {
        if (this.status >= 200 && this.status < 400) {
            var data = JSON.parse(this.response);
            console.log(data);
            var content = '';
            for (var i = 0; i < data.length; i++) {

                content += `
                <div onclick="zoomToPointer(${data[i].coord_x}, ${data[i].coord_y})" class="bar-search-item font-medium-italic">${data[i].name} - ${data[i].address}</div>
                `;
            }
            document.getElementById('bar-search-content').innerHTML = content;
        } else {
            console.error('Error al cargar los puntos');
        }
    };

    ajax.onerror = function() {
        loading(false);
        console.error('Error de red');
    };

    ajax.send(formdata);
}

function zoomToPointer(lat, lng) {
    map.setView([lat, lng], 17);
    alterSearchDiv(false);
}

/* APP CONTROLER */

/* SESSIONS FUNCTIONALITIES */

function displaySessions(gincana) {
    loading(true);
    currentGincana = gincana.id;

    fetch(`/api/sessions/?id=${gincana.id}`)
    .then((res) => res.text())
    .then((text) => {
        appContent.innerHTML = `
            <div class="bottom-gincana-selected">
                <h1 class="font-bold bottom-gincana-selected-title">${gincana.name}</h1>
                <p class="font-medium bottom-gincana-selected-desc">${gincana.desc}</p>
                <span class="bottom-gincana-selected-creator">Creada por: ${gincana.user}</span>
                <span class="bottom-gincana-selected-checkpoints">Puntos de control: ${gincana.gincana_points_count}</span>
                <h1 class="font-bold bottom-gincana-selected-title">Sesiones activas</h1>
        `;

        let sessions = JSON.parse(text);
        let content = "";

        sessions.forEach(session => {
            content += `
                <div class="bottom-gincana-selected-session">
                    <div class="bottom-gincana-selected-session-container">
                        <p class="bottom-gincana-selected-session-container-title">${session.name}</p>
                        <p class="bottom-gincana-selected-session-container-creator">Creador: ${session.session_admin.name}</p>
                    </div>
                    <div onclick="displayGroups(${session.id})" class="bottom-gincana-selected-session-join">
                        <img src="../img/arrow_up_icon.png" alt="ver">
                    </div>
                </div>
            `;
        });
        
        appContent.innerHTML += content + "</div>";
        loading(false);
    })
    .catch(error => {
        console.error(error);
        loading(false);
    });

    document.getElementById('reload-button').onclick = () => displaySessions(gincana);
}



/* GROUPS FUNCTIONALITIES */

function displayGroups(sessionId) {
    loading(true);
    currentSession = sessionId;

    fetch(`/api/groups/?id=${sessionId}`)
    .then((res) => res.text())
    .then((text) => {
        let groups = JSON.parse(text);
        let content = "";

        content = `
            <div class="bottom-gincana-session">
                <h1 class="font-bold bottom-gincana-session-title">${groups.session.gincana.name}</h1>
                <p class="font-medium-italic bottom-gincana-session-name">${groups.session.name} [${groups.session.session_code}]</p>
                <div class="bottom-gincana-session-groups-title-container">
                    <span class="font-medium bottom-gincana-session-groups-title">Grupos de la sesión</span>
                    <div class="bottom-gincana-session-groups-create">
                        <img src="../img/create_group_icon.png" alt="create">
                    </div>
                </div>
        `;

        groups.groups.forEach(group => {
            let members = "";
            group.gincana_session_group_users.forEach(user => {
                members += `<div class="font-medium bottom-gincana-session-group-user"><span>${user.user.name}</span></div>`;
            });

            content += `
                <div class="bottom-gincana-session-group">
                    <span class="font-medium bottom-gincana-session-group-title">${group.name}</span>

                    ${members}

                    <div onclick="joinGroup(${group.id})" class="bottom-gincana-session-group-join-container">
                        <div class="bottom-gincana-session-group-join">
                            <img src="../img/join_icon.png" alt="join">
                        </div>
                    </div>
                    
                </div>
            `;
        });
        
        appContent.innerHTML = content;
        document.getElementById('reload-button').onclick = () => displayGroups(sessionId);
        loading(false);
    })
    .catch(error => {
        console.error(error);
        loading(false);
    });
}


function joinGroup(groupId) {    
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let data = {
        'gincana_session_group_id': groupId
    };

    fetch('/api/groups/join/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(data)
    })
    .then(() => {
        changeTab(3);
        disableTab(2);
        enableTab(3);
        displayCurrentActivityStatus();
        document.getElementById('current-activity-button').style.display = 'flex';
    })
}


/* CURRENT ACTIVITY FUNCTIONALITIES */

function displayCurrentActivityStatus() {
    loading(true);

    fetch('/api/current-activity/status/')
    .then((res) => res.text())
    .then((text) => {
        let activity = JSON.parse(text);

        appContent.innerHTML = `
            <div class="bottom-gincana-mysession">
                <h1 class="font-bold bottom-gincana-session-title">${activity.gincana.name}</h1>
                <p class="font-medium-italic bottom-gincana-session-name">${activity.session.name} [${activity.session.session_code}]</p>
                <p class="font-medium-italic bottom-gincana-session-name">${activity.session.status == 0 ? `Esperando jugadores` : `En juego`}</p>
            `;

        if (!activity.ranking) {
            if (activity.session.status == 1) {
                activity.available_points.forEach((point, index) => {
                    appContent.innerHTML += `
                        <div class="bottom-gincana-mysession-clue-container">
                            <div class="bottom-gincana-mysession-clue-title">
                                <h1 class="font-medium">Punto ${index + 1}</h1>
                            </div>
                            <div class="bottom-gincana-mysession-clue">
                                <span class="font-medium bottom-gincana-mysession-clue-text">Pista: ${point.hint ? point.hint : ''}</span>
                            </div>
                            <span class="font-medium-italic bottom-gincana-mysession-clue-left">Miembros restantes: ${activity.group.gincana_session_group_users_count}</span>
                            <br>
                            <span class="font-medium-italic bottom-gincana-mysession-clue-arrived">Han llegado: ${point.members_in_point}</span>
                        </div>
                    `;
                });
            }

            appContent.innerHTML += `
                <div class="bottom-gincana-session-groups-title-container">
                    <span class="font-medium bottom-gincana-mysession-groups-title">${activity.group.name}</span>
                    ${activity.session.status != 1 ? 
                        `<div class="bottom-gincana-mysession-groups-create">
                            <img onclick="exitGroup()" src="../img/exit_icon.png" alt="create">
                        </div>` : 
                        `<div class="bottom-gincana-mysession-groups-create">
                            <img onclick="leaveGroup()" src="../img/exit_icon.png" alt="create">
                        </div>`}
                </div>
            `;

            activity.group.gincana_session_group_users.forEach(member => {
                appContent.innerHTML += `
                    <div class="bottom-gincana-mysession-group">
                        <span class="font-medium bottom-gincana-mysession-group-title">${member.user.name}</span>
                    </div>`;
            });

            if (activity.session.is_owner && activity.session.status != 1) {
                document.getElementById('play-activity-button').style.display = 'flex';
                document.getElementById('play-activity-button').onclick = () => {
                    startGincanaSession();
                }
            } else {
                document.getElementById('play-activity-button').style.display = 'none';
            }

        } else {
            appContent.innerHTML += `<h1 class="font-bold bottom-gincana-session-title">¡Enhorabuena, has terminado la gincana!</h1>`;

            activity.ranking.forEach(group => {
                appContent.innerHTML += `
                    <div class="bottom-gincana-finished-position-container">
                        <span class="font-bold bottom-gincana-finished-position">#${group.group_position}: ${group.group_name}</span>
                    </div>`;
            });

            appContent.innerHTML += `
                <div class="bottom-gincana-finished-left-container">
                    <div onclick="exitCurrentActivity()" class="bottom-gincana-finished-left">
                        <img src="../img/exit_icon.png" alt="leave">
                    </div>
                </div>`;
        }

        pointers = [];

        activity.available_points.forEach((point, index) => {
            if (point.id) {
                pointers.push({
                    id: point.id,
                    name: point.name,
                    img: '../img/checkpoint_mark.png',
                    pointer_img: '../img/checkpoint_mark.png',
                    coord_x: point.coord_x,
                    coord_y: point.coord_y,
                });
            }
        });

        document.getElementById('reload-button').onclick = () => displayCurrentActivityStatus();
        loading(false);
        loadPointers('gincana_points');
    })
    .catch(error => {
        console.error(error);
        loading(false);
    });
}


function exitGroup() {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/api/groups/exit/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(() => {
        inActivity = false;
        disableTab(3);
        enableTab(2);
        changeTab(2);
        openBottomContainer(false);
    })
}


function leaveGroup() {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/api/groups/leave/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(() => {
        inActivity = false;
        disableTab(3);
        enableTab(2);
        changeTab(2);
        openBottomContainer(false);
    })
}


function startGincanaSession() {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/api/sessions/start/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(() => {
        displayCurrentActivityStatus();
    })
}


function exitCurrentActivity() {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/api/current-activity/exit/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(() => {
        inActivity = false;
        disableTab(3);
        enableTab(2);
        changeTab(2);
    })
}


function sendCheckpoint(position) {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let data = {
        'coord_x': position['lat'],
        'coord_y': position['lng']
    };

    fetch('/api/current-activity/checkpoint/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(data)
    })
    .then((res) => res.text())
    .then((text) => {
        let response = JSON.parse(text);

    /*  if (response['result'] == 'ok') {
            displayCurrentActivityStatus();
            document.getElementById('check-result-msg').textContent = 'Punto encontrado';

        } else if (response['result' ]== 'wait') {
            document.getElementById('check-result-msg').textContent = 'Espera a que el resto lleguen al punto';

        } else if (response['result'] == 'no')  {
            document.getElementById('check-result-msg').textContent = 'No se ha encontrado el punto';

        } */

    })
}

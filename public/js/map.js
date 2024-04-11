var map = null;
var pointers = [];
var userPointer = { name: 'Nombre de Usuario', pointer_img: '../img/me_icon.png', coord_x: 0, coord_y: 0 };
var userLayer = null;
var pointersLayer = null;
var userId = null;


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
    disableTab(3);
    loading(false);
}

function UserId(id) {
    userId = id;
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
        fetch('/api/points')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al cargar los puntos');
                }
                return response.json();
            })
            .then(data => {
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
            })
            .catch(error => {
                loading(false);
                console.error(error);
            });
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
                        pointer_img: '../img/gincana_icon.png',
                        user: data[i].user
                    });
                }
                
                UpdateMapPointers();
            })
            .catch(error => {
                loading(false);
                console.error(error);
            });
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

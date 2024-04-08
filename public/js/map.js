var map = null;
var pointers = [];
var userPointer = { name: 'Nombre de Usuario', pointer_img: '../img/me_icon.png', coord_x: 0, coord_y: 0 };
var userLayer = null;
var pointersLayer = null;


window.onload = function () {
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

function initMap() {
    map = L.map('background', { zoomControl: false }).fitWorld();

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    map.getContainer().style.zIndex = 0;

    userLayer = L.layerGroup().addTo(map);
    pointersLayer = L.layerGroup().addTo(map);

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

var pointersType = 'ubicaciones';
function loadPointers(type) {
    if (type == pointersType) return;
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
                pointers = [];
                for (var i = 0; i < data.length; i++) {
                    pointers.push({
                        id: data[i].id,
                        name: data[i].name,
                        pointer_img: data[i].main_category_img,
                        pointer_col: data[i].main_category_color,
                        img: data[i].img,
                        coord_x: data[i].coord_x,
                        coord_y: data[i].coord_y,
                        categories: data[i].categories,
                        address: data[i].address,
                        desc: data[i].desc
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
}
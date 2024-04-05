var map = null;
var pointers = [];
var userPointer = { img: 'img/me_icon.png', coord_x: 0, coord_y: 0, name: 'Nombre de Usuario' };
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

function UpdateUserLocation() {
    getGeoLocation().then(position => {
        userPointer.coord_x = position.lat;
        userPointer.coord_y = position.lng;

        userLayer.clearLayers();

        var icon = L.icon({
            iconUrl: userPointer.img,
            iconSize: [96, 96],
            iconAnchor: [48, 96]
        });

        L.marker([userPointer.coord_x, userPointer.coord_y], { icon: icon })
            .bindTooltip(userPointer.name, { // Asume que cada puntero tiene una propiedad 'name'
                permanent: true, // El texto siempre será visible
                direction: 'center', // El texto aparecerá en el centro del marcador
                offset: [-80, -48], // Desplaza el texto hacia arriba para que esté en el centro de la imagen
                className: 'my-tooltip' // Asigna una clase personalizada al tooltip
            })
            .addTo(userLayer);

    }).catch(error => {
        console.error(error);
    });
}

function UpdateMapPointers() {
    pointersLayer.clearLayers();
    for (var i = 0; i < pointers.length; i++) {
        var icon = L.icon({
            iconUrl: pointers[i].img,
            iconSize: [64, 64],
            iconAnchor: [32, 64]
        });
        L.marker([pointers[i].coord_x, pointers[i].coord_y], { icon: icon })
            .bindTooltip(pointers[i].name, { // Asume que cada puntero tiene una propiedad 'name'
                permanent: true, // El texto siempre será visible
                direction: 'center', // El texto aparecerá en el centro del marcador
                offset: [-64, -32], // Desplaza el texto hacia arriba para que esté en el centro de la imagen
                className: 'my-tooltip' // Asigna una clase personalizada al tooltip
            })
            .addTo(pointersLayer);
    }
}

function zoomIn() {
    map.zoomIn();
}

function zoomOut() {
    map.zoomOut();
}
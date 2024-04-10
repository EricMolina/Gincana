var oldTab = 3;
function changeTab(tab) {
    if (tab == oldTab) return;
    if (document.getElementById('tab'+tab).classList.contains('footer-item-disabled')) return;
    document.getElementById('tab'+tab).classList.add('footer-item-selected');
    document.getElementById('tab'+oldTab).classList.remove('footer-item-selected');
    oldTab = tab;
    if (tab == 1) { //Ubicaciones
        loadPointers('ubicaciones');
        document.getElementById('current-activity-button').style.display = 'none';
        document.getElementById('create-gincana-button').style.display = 'none';
    } else if (tab == 2) { //Gincanas
        loadPointers('gincanas');
        document.getElementById('current-activity-button').style.display = 'none';
        document.getElementById('create-gincana-button').style.display = 'flex';
    } else { //Mi actividad
        console.log('b')
        document.getElementById('current-activity-button').style.display = 'flex';
        document.getElementById('create-gincana-button').style.display = 'none';
        document.getElementById('current-activity-button').onclick = () => {
            openBottomContainer(true);
            displayCurrentActivityStatus();
        }
    }
}


function disableTab(tab) {
    document.getElementById('tab'+tab).classList.add('footer-item-disabled');
    if (tab == 1) {
        document.getElementById('tab'+tab).getElementsByTagName('img')[0].src = "../img/icon_map.png";
    } else if (tab == 1) {
        document.getElementById('tab'+tab).getElementsByTagName('img')[0].src = "../img/icon_gincanas.png";
    } else {
        document.getElementById('tab'+tab).getElementsByTagName('img')[0].src = "../img/icon_activity.png";
    }
}

function enableTab(tab) {
    if (!document.getElementById('tab'+tab).classList.contains('footer-item-disabled')) return;
    
    document.getElementById('tab'+tab).classList.remove('footer-item-disabled');
    if (tab == 1) {
        document.getElementById('tab'+tab).getElementsByTagName('img')[0].src = "../img/icon_map_selected.png";
    } else if (tab == 1) {
        document.getElementById('tab'+tab).getElementsByTagName('img')[0].src = "../img/icon_gincanas_selected.png";
    } else {
        document.getElementById('tab'+tab).getElementsByTagName('img')[0].src = "../img/icon_activity_selected.png";
    }
}

var howManyLoadings = 0;
function loading(b) {
    if (b) {
        howManyLoadings++;
        document.getElementById('load').style.display = 'flex';
    } else {
        howManyLoadings--;
        if (howManyLoadings < 0) howManyLoadings = 0;
        
        if (howManyLoadings > 0) return;
        document.getElementById('load').style.display = 'none';
    }
}

function openUserProfile(b) {
    if (b) {
        document.getElementById('user-profile-container').style.display = 'inline-block';
    } else {
        document.getElementById('user-profile-container').style.display = 'none';
    }
}

function openBottomContainer(b) {
    if (b) {
        document.getElementById('bottom-container').style.display = 'inline-block';
    } else {
        document.getElementById('bottom-container').style.display = 'none';
    }
}

function changeUserPhoto(user_id) {

}

function createUserLabel(user_id) {

}

function reloadContent() {
    loading(true);


    loading(false);
}
var numPuntos = 0;
function openGincanaModal(){
    numPuntos == 0;
    var ajax = new XMLHttpRequest();
    ajax.open('get', 'api/gincanas/create/');
    ajax.onload=function(){
        if(ajax.status == 200){
            Swal.fire({
                showConfirmButton: false,
                html: ajax.responseText,
            });
            getGeoLocation().then(position => {
                //position.lat  X
                //position.lng  Y
                var mapGin = L.map('mapNewGincana').setView([position.lat, position.lng], 14);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(mapGin);
                var marker = L.marker([position.lat,position.lng]).addTo(mapGin);
                document.getElementById("coord").innerText = `${position.lat} - ${position.lng}`
                document.getElementById("coordx").value = position.lat;
                document.getElementById("coordy").value = position.lng;
                function onMapClick(e) {
                    mapGin.eachLayer((layer) => {
                        if (layer instanceof L.Marker) {
                            layer.remove();
                        }
                    });
                    var coord = e.latlng;
                    var lat = coord.lat;
                    var lng = coord.lng;
                    var marker = L.marker([lat,lng]).addTo(mapGin);
                    document.getElementById("coord").innerText = `${lat} - ${lng}`
                    document.getElementById("coordx").value = lat;
                    document.getElementById("coordy").value = lng;
                }
                mapGin.on('click', onMapClick);
            })
        }
    }
    ajax.send();
}
function NuevoPunto(){
    fetch('/api/points/').then(response => {return response.json();})
    .then(data => {
        numPuntos ++;
        var newPoint = `
        <div id ="${numPuntos}">
        <p>Punto ${numPuntos}</p>
        <select name="points[]">`;
        data.forEach(point => {
            newPoint +=`<option value="${point.id}">${point.name}</option>`
        });
        newPoint +=`</select>
        <br>
        <label>Pista</label>
        <br>
        <textarea name="hints[]" rows="2" cols="20"></textarea><br>
        <button onclick="deletePoint(${numPuntos})">Borrar</button>
        </div>`
        document.getElementById("puntos").innerHTML +=newPoint;
    })
}
function deletePoint(id){
    numPuntos --;
    document.getElementById(id).remove();
    var puntos = document.getElementById("puntos").children
    for (let i = 0; i < puntos.length; i++) {
        var puntoPos = i+1
        puntos[i].children[0].innerText = `Punto ${puntoPos}`
    }
}
function crearGin(){
    var frm = document.getElementById("ginForm");
    var formdata = new FormData(frm);
    var ajax = new XMLHttpRequest();
    ajax.open('post', 'api/gincanas/');
    ajax.onload=function(){
        if(ajax.status == 200){
            if(ajax.responseText == "ok"){
                swal.close();
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 1000,
                    timerProgressBar: true,
                  });
                  Toast.fire({
                    icon: "success",
                    title: "usuario borrado correctamente"
                  });
            }else{
                document.getElementById("error").innerText = ajax.responseText;
            }
        }
    }
    ajax.send(formdata);
}
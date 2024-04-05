// Mapa
var map = L.map('map').setView([41.350030, 2.107861], 14);
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);
function createPoint(e) {
    var coord = e.latlng;
    var lat = coord.lat;
    var lng = coord.lng;
    // var marker = L.marker([lat,lng]).addTo(map);
    Swal.fire({
        showConfirmButton: false,
        width: '50%',
        html:`<a id="closeModal" onclick="swal.close(); return false;">x</a><h1>Nuevo punto</h1>
        <div class="fila">
            <form id="modForm" enctype="multipart/form-data">
                <div class="colLista">
                    <input type="hidden" name="coordx" id="coordx" value="${lat}">
                    <input type="hidden" name="coordy" id="coordy" value="${lng}">
                    <label for="name">Nombre</label>
                    <br>
                    <input type="text" name="name" id="name">
                    </br>
                    <label for="address">Dirección</label>
                    </br>
                    <input type="text" name="address" id="address">
                    <input type="file" name="img" id="img">
                    <div id="preview" style="background-image: url('');" class="imgPoint"></div>
                    </br>
                    <label for="labelMain">Label principal</label>
                    <div id="labelList"></div>
                </div>
                <div class="colMapa">
                    <label for="ubi">Ubicación</label>
                    <p id="coord">${lat} - ${lng}</p>
                    <div id="mapNew" class="mapa"></div>
                </div>
                
            </form>
        </div>
        <p id="error"></p><button onclick="newPoint()">Crear</button>`
    });
    var labelList = document.getElementById("labelList");
    var ajax = new XMLHttpRequest();
    ajax.open('get', '/label/getlabel');
    ajax.onload = function(){
        if(ajax.status == 200){
            var labels = JSON.parse(ajax.responseText)
            var labelForm = `<select name="labelMain" id="labelMain">`
            var labelchk = "";
            labelchk += `</br><fieldset id="labelsList"><legend>Labels</legend>`
            labels.forEach(label => {
                labelForm +=`<option value="${label.id}" selected>${label.name}</option>`;
                labelchk +=`<label for='${label.id}'>${label.name}</label>`;
                labelchk +=`<input type='checkbox' name='lab[]' id='${label.id}' value='${label.name}'>`;
            });
            labelForm +=`</label>`
            labelchk +=`</fieldset>`
            labelList.innerHTML = labelForm;
            labelList.innerHTML += "</br>";
            labelList.innerHTML += labelchk;
        }
    }
    ajax.send();
    var file = document.getElementById("img");
    var profilePreview = document.getElementById("preview");
    file.addEventListener("change",()=>{getImg()})
    var mapNew = L.map('mapNew').setView([lat, lng], 14);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(mapNew);
    var marker = L.marker([lat,lng]).addTo(mapNew);
    function onMapClick(e) {
        mapNew.eachLayer((layer) => {
            if (layer instanceof L.Marker) {
                layer.remove();
            }
        });
        var coord = e.latlng;
        var lat = coord.lat;
        var lng = coord.lng;
        var marker = L.marker([lat,lng]).addTo(mapNew);
        document.getElementById("coord").innerText = `${lat} - ${lng}`
        document.getElementById("coordx").value = lat;
        document.getElementById("coordy").value = lng;
    }
    mapNew.on('click', onMapClick);
}
map.on('click', createPoint);
// Lista de puntos
src = document.getElementById("src");
var csrf_token = document.querySelector("meta[name = 'csrf-token']").getAttribute('content');
function getPoints(){
    map.eachLayer((layer) => {
        if (layer instanceof L.Marker) {
            layer.remove();
        }
    });
    var ajax = new XMLHttpRequest();
    ajax.open('POST', '/admin/point/list');
    var formdata = new FormData();
    formdata.append('src', src.value);
    formdata.append('_token', csrf_token);
    ajax.onload = function(){
        var json = JSON.parse(ajax.responseText);
        if(ajax.status == 200){
            // console.log(json)
            // console.log(json)
            var points="";
            json.forEach(point => {
                var marker = L.marker([point.coord_x,point.coord_y]).addTo(map);
                marker.addEventListener("click",()=>{modForm(point.id)});
                points +=`<div onclick="modForm(${point.id})"><img src="../img/points/${point.img}" style="float:left" class="imgPoint"><h3>${point.name}</h3><p>${point.address}</p><p>${point.coord_x} - ${point.coord_y}</p></div>`;
            });
            document.getElementById("pointList").innerHTML = points;
        }
    }
    ajax.send(formdata);
}
function modForm(id){
    var ajax = new XMLHttpRequest();
    ajax.open('POST', '/admin/point/show');
    var formdata = new FormData();
    formdata.append('id', id);
    formdata.append('_token', csrf_token);
    ajax.onload = function(){
        if(ajax.status == 200){
            console.log(ajax.responseText);
            var data = JSON.parse(ajax.responseText);
            Swal.fire({
                showConfirmButton: false,
                width: '50%',
                html:`<a id="closeModal" onclick="swal.close(); return false;">x</a><h1>Editando ${data.name}</h1>
                <div class="fila">
                    <form id="modForm" enctype="multipart/form-data">
                        <div class="colLista">
                            <input type="hidden" name="id" id="labelId" value="${data.id}">
                            <input type="hidden" name="filename" id="filename" value="${data.img}">
                            <input type="hidden" name="coordx" id="coordx" value="${data.coord_x}">
                            <input type="hidden" name="coordy" id="coordy" value="${data.coord_y}">
                            <label for="name">Nombre</label>
                            <br>
                            <input type="text" name="name" id="name" value="${data.name}">
                            </br>
                            <label for="address">Dirección</label>
                            </br>
                            <input type="text" name="address" id="address" value="${data.address}">
                            </br>
                            <input type="file" name="img" id="img">
                            <div id="preview" style="background-image: url('../img/points/${data.img}');" class="imgPoint"></div>
                            </br>
                            <label for="labelMain">Label principal</label>
                            <div id="labelList"></div>
                        </div>
                        <div class="colMapa">
                            <label for="ubi">Ubicación</label>
                            <p id="coord">${data.coord_x} - ${data.coord_y}</p>
                            <div id="mapMod" class="mapa"></div>
                        </div>
                        
                    </form>
                </div>
                <p id="error"></p><button onclick="update()">Modificar</button><button onclick="deletePoint(${data.id})">Borrar</button>`
            });
            labelFormMain()
            var file = document.getElementById("img");
            var profilePreview = document.getElementById("preview");
            file.addEventListener("change",()=>{getImg()})
            var mapMod = L.map('mapMod').setView([data.coord_x, data.coord_y], 14);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(mapMod);
            var marker = L.marker([data.coord_x,data.coord_y]).addTo(mapMod);
            function onMapClick(e) {
                mapMod.eachLayer((layer) => {
                    if (layer instanceof L.Marker) {
                        layer.remove();
                    }
                });
                var coord = e.latlng;
                var lat = coord.lat;
                var lng = coord.lng;
                var marker = L.marker([lat,lng]).addTo(mapMod);
                document.getElementById("coord").innerText = `${lat} - ${lng}`
                document.getElementById("coordx").value = lat;
                document.getElementById("coordy").value = lng;
            }
            mapMod.on('click', onMapClick);
            
        }
    }
    ajax.send(formdata);

}
function update(){
    var frm = document.getElementById("modForm");
    var formdata = new FormData(frm);
    formdata.append('_token', csrf_token);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', '/admin/point/update');
    ajax.onload=function(){
        if(ajax.status == 200){
            // console.log(ajax.responseText);
            if(ajax.responseText == "ok"){
                getPoints()
                document.getElementById("closeModal").click();
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 1000,
                    timerProgressBar: true,
                  });
                  Toast.fire({
                    icon: "success",
                    title: "Cambios aplicados correctamente"
                  });
            }else{
                document.getElementById("error").innerText = ajax.responseText;
            }
        }
    }
    ajax.send(formdata);
}
function newPoint(){
    var frm = document.getElementById("modForm");
    var formdata = new FormData(frm);
    formdata.append('_token', csrf_token);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', '/admin/point/store');
    ajax.onload=function(){
        if(ajax.status == 200){
            if(ajax.responseText == "ok"){
                getPoints()
                document.getElementById("closeModal").click();
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 1000,
                    timerProgressBar: true,
                  });
                  Toast.fire({
                    icon: "success",
                    title: "Punto creado correctamente"
                  });
            }else{
                document.getElementById("error").innerText = ajax.responseText;
            }
        }
    }
    ajax.send(formdata);
}
function deletePoint(id){
    Swal.fire({
        title: `Seguro que quieres eliminar este punto?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "No",
        confirmButtonText: "Si"
      }).then((result) => {
        if (result.isConfirmed) {
            var ajax = new XMLHttpRequest();
            var formdata = new FormData();
            formdata.append('id', id);
            formdata.append('_token', csrf_token);
            ajax.open('POST', '/admin/point/delete');
            ajax.onload = function(){
                if(ajax.status == 200){
                    getPoints();
                    if(ajax.responseText == "ok"){
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: true,
                          });
                          Toast.fire({
                            icon: "success",
                            title: "Punto borrado correctamente"
                          });
                    }else{
                        Swal.fire({
                            title: "Error",
                            text: "Se ha encontrado un error",
                            icon: "warning"
                          });
                    }
                }
            }
            ajax.send(formdata);
        }
      });
}
function getImg(){
    var file = document.getElementById("img");
    var profilePreview = document.getElementById("preview");
    if (file.files && file.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            // profilePreview.style.visibility = "visible";
            profilePreview.style.backgroundImage = `url(${e.target.result})`;
        };
        reader.readAsDataURL(file.files[0]);
    }
}
function labelFormMain(){
    var labelList = document.getElementById("labelList");
    var ajax = new XMLHttpRequest();
    ajax.open('get', '/label/getlabel');
    ajax.onload = function(){
        if(ajax.status == 200){
            var labels = JSON.parse(ajax.responseText)
            var labelForm = `<select name="labelMain" id="labelMain">`
            var labelchk = "";
            labelchk += `</br><fieldset id="labelsList"><legend>Labels</legend>`
            labels.forEach(label => {
                labelForm +=`<option value="${label.id}" selected>${label.name}</option>`;
                labelchk +=`<label for='${label.id}'>${label.name}</label>`;
                labelchk +=`<input type='checkbox' name='lab[]' id='${label.id}' value='${label.name}'>`;
            });
            labelForm +=`</label>`
            labelchk +=`</fieldset>`
            labelList.innerHTML = labelForm;
            labelList.innerHTML += "</br>";
            labelList.innerHTML += labelchk;
        }
    }
    ajax.send();
}
window.onload=getPoints();

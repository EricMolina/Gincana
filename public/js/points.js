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
        width: '70%',
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
                    <br>
                    <label for="desc">Descripción</label>
                    <br>
                    <textarea name="desc" id = "desc" rows="2" cols="35" style="resize: none"></textarea>
                    <br>
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
                labelchk +=`<input type='checkbox' name='lab[]' id='${label.id}' value='${label.id}'>`;
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
    loading(true);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', '/admin/point/list');
    var formdata = new FormData();
    formdata.append('src', src.value);
    formdata.append('_token', csrf_token);
    ajax.onload = function(){
        loading(false);
        var json = JSON.parse(ajax.responseText);
        if(ajax.status == 200){
            var points="<table><tr><th>Image</th><th>Name</th><th>Address</th><th>Coordinates</th></tr>";
            json.forEach(point => {
                var pointImg = "../img/points/"+point.img;
                if (point.img == null) pointImg = "../img/default_image.png";
                var marker = L.marker([point.coord_x,point.coord_y]).addTo(map);
                marker.addEventListener("click",()=>{modForm(point.id)});
                points +=`<tr onclick="modForm(${point.id})">
                            <td><img src="${pointImg}" class="imgPoint"></td>
                            <td>${point.name}</td>
                            <td>${point.address}</td>
                            <td>${point.coord_x} - ${point.coord_y}</td>
                           </tr>`;
            });
            points += "</table>";
            document.getElementById("pointList").innerHTML = points;
        }
    }
    ajax.send(formdata);
}
function modForm(id){
    var ajax = new XMLHttpRequest();
    ajax.open('POST', '/admin/point/show');
    var formdata = new FormData();
    loading(true);
    formdata.append('id', id);
    formdata.append('_token', csrf_token);
    ajax.onload = function(){
        loading(false);
        if(ajax.status == 200){
            var data = JSON.parse(ajax.responseText);
            // console.log(data.main_label_id);
            var pointImg = data.img;
            if (data.img == null) pointImg = "default_image.png";
            Swal.fire({
                showConfirmButton: false,
                width: '70%',
                html:`<a id="closeModal" onclick="swal.close(); return false;">x</a><h1>Editando ${data.name}</h1>
                <div class="fila">
                    <form id="modForm" class="crud-form colLista" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="labelId" value="${data.id}">
                            <input type="hidden" name="filename" id="filename" value="${pointImg}">
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
                            <label for="desc">Descripción</label>
                            <br>
                            <textarea name="desc" id = "desc" rows="2" cols="35" style="resize: none">${data.desc}</textarea>
                            <br>
                            <input type="file" name="img" id="img">
                            <div id="preview" style="background-image: url('../img/points/${pointImg}');" class="imgPoint"></div>
                            </br>
                            <label for="labelMain">Label principal</label>
                            <div id="labelList"></div>
                    </form>
                    <div class="colMapa colMapaMod">
                        <label for="ubi">Ubicación</label>
                        <p id="coord">${data.coord_x} - ${data.coord_y}</p>
                        <div id="mapMod" class="mapa"></div>
                        <div class="shadowMod"></div>
                    </div>
                </div>
                <p id="error"></p><button onclick="update()">Modificar</button><button onclick="deletePoint(${data.id})">Borrar</button>`
            });
            labelFormMain(data.labels,data.main_label_id)
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
    loading(true);
    ajax.open('POST', '/admin/point/update');
    ajax.onload=function(){
        loading(false);
        if(ajax.status == 200){
            console.log(ajax.responseText);
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
    loading(true);
    ajax.open('POST', '/admin/point/store');
    ajax.onload=function(){
        loading(false);
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
            loading(true);
            var ajax = new XMLHttpRequest();
            var formdata = new FormData();
            formdata.append('id', id);
            formdata.append('_token', csrf_token);
            ajax.open('POST', 'point/delete');
            ajax.onload = function(){
                loading(false);
                if(ajax.status == 200){
                    getPoints();
                    // console.log(JSON.parse(ajax.responseText))
                    console.log(ajax.responseText)
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
function labelFormMain(inputs, main){
    var inputArray = [];
    inputs.forEach(elem=>{
        inputArray.push(elem.id)
    })
    // console.log(inputArray);
    var labelList = document.getElementById("labelList");
    loading(true);
    var ajax = new XMLHttpRequest();
    ajax.open('get', '/label/getlabel');
    ajax.onload = function(){
        loading(false);
        if(ajax.status == 200){
            var labels = JSON.parse(ajax.responseText)
            var labelForm = `<select name="labelMain" id="labelMain">`
            var labelchk = "";
            labelchk += `</br><fieldset id="labelsList"><legend>Labels</legend>`
            labels.forEach(label => {
                labelForm +=`<option value="${label.id}" selected>${label.name}</option>`;
                labelchk +=`<label for='${label.id}'>${label.name}</label>`;
                if(inputArray.includes(label.id)){
                    labelchk +=`<input type='checkbox' name='lab[]' id='${label.id}' value='${label.id}' checked><br>`;
                }else{
                    labelchk +=`<input type='checkbox' name='lab[]' id='${label.id}' value='${label.id}'><br>`;
                }
            });
            labelForm +=`</label>`
            labelchk +=`</fieldset>`
            labelList.innerHTML = labelForm;
            labelList.innerHTML += "</br>";
            labelList.innerHTML += labelchk;
            labelList.innerHTML += "</br>";

            document.getElementById("labelMain").value = main
            // console.log(document.getElementById("labelsList").children);
            var form  = document.getElementById("labelsList");
            var inputs = form.querySelectorAll("input")
        }
    }
    ajax.send();
}
window.onload=getPoints();

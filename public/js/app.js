var oldTab = 3;

var labelFilters = { 1:0};
var userLabelFilters = { 1:0};
function changeTab(tab) {
    if (tab == oldTab) return;
    if (document.getElementById('tab'+tab).classList.contains('footer-item-disabled')) return;
    document.getElementById('tab'+tab).classList.add('footer-item-selected');
    document.getElementById('tab'+oldTab).classList.remove('footer-item-selected');
    oldTab = tab;
    if (tab == 1) { //Ubicaciones
        loadPointers('ubicaciones');
    } else if (tab == 2) { //Gincanas
        loadPointers('gincanas');
    } else { //Mi actividad

    }

    loadDefaultData();
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

function loadDefaultData() {
    loading(true);
    labelFilters = {};
    userLabelFilters = {};
    fetch('/user/data')
            .then(response => {
                loading(false);
                if (!response.ok) {
                    throw new Error('Error al cargar los puntos');
                }
                return response.json();
            })
            .then(data => {
                console.log(data);

                var userContent = '';
                var headerContent = '';
                for (var i = 0; i < data.labels.length; i++) {
                    headerContent += `
                    <div onclick="alterLabelFilter(${data.labels[i].id})" class="header-label" id="header-label-${data.labels[i].id}">
                        <span class="font-light">${data.labels[i].name}</span>
                    </div>
                    `;
                    labelFilters[data.labels[i].id] = 0;
                }
                for (var i = 0; i < data.user.user_label.length; i++) {
                    headerContent += `
                    <div onclick="alterUserLabelFilter(${data.user.user_label[i].id})" class="header-user-label" id="header-user-label-${data.user.user_label[i].id}">
                        <span class="font-light">${data.user.user_label[i].name}</span>
                    </div>
                    `;
                    userContent += `
                    <div onclick="removeUserLabel(${data.user.user_label[i].id})" class="profile-label">
                        <span class="font-light">${data.user.user_label[i].name}</span>
                    </div>
                    `;
                    userLabelFilters[data.user.user_label[i].id] = 0;
                }
                document.getElementById('user-profile-labels').innerHTML = userContent;
                document.getElementById('header-content-labels').innerHTML = headerContent;
            })
            .catch(error => {
                loading(false);
                console.error(error);
            });
}

function createUserLabel(user_id) {
    Swal.fire({
        showConfirmButton: false,
        html:`<a id="closeModal" onclick="swal.close(); return false;">Cerrar</a><h1>Nueva etiqueta personal</h1>
        <form onsubmit="return false;" id="new-label-form" class="crud-form">
            <label for="name">Nombre</label>
            <br>
            <input type="text" name="name" id="name">
            <br>
            <button onclick="newUserLabel(${user_id})">Crear</button>
        </form>
        <p id="error"></p>`
    });
}

function newUserLabel(user_id){
    var frm = document.getElementById("new-label-form");
    var formdata = new FormData(frm);
    var csrf_token = document.querySelector("meta[name = 'csrf-token']").getAttribute('content');
    formdata.append('_token', csrf_token);
    formdata.append('user_id', user_id);
    var ajax = new XMLHttpRequest();
    loading(true);
    ajax.open('POST', '/userlabel/store');
    ajax.onload=function(){
        loading(false);
        if(ajax.status == 200){
            console.log(ajax.responseText)
            if(ajax.responseText == "ok"){
                loadDefaultData();
                Swal.fire("Etiqueta creada correctamente", "", "success");
            } else{
                document.getElementById("error").innerText = ajax.responseText;
            }
        }
    }
    ajax.send(formdata);
}


function assignUserLabel(user_id, point_id) {
    var ajax = new XMLHttpRequest();
    loading(true);
    ajax.open('GET', `/userlabel/${user_id}`);
    ajax.onload=function() {
        loading(false);
        if(ajax.status == 200){
            //convierte ajax.responseText a JSON
            var data = JSON.parse(ajax.responseText);
            var opciones = '';
            for (var i = 0; i < data.length; i++) {
                opciones += `<option value="${data[i].id}">${data[i].name}</option>`;
            }
            if(ajax.status == 200){
                Swal.fire({
                    showConfirmButton: false,
                    html:`<a id="closeModal" onclick="swal.close(); return false;">Cerrar</a><h1>Asignar etiqueta personal</h1>
                    <form onsubmit="return false;" id="new-labelpoint-form" class="crud-form">
                        <label for="label_id">Nombre</label>
                        <br>
                        <select id="label_id" name="label_id">
                            ${opciones}
                        </select>
                        <br>
                        <button onclick="newUserLabelPoint(${user_id}, ${point_id})">Asignar</button>
                    </form>
                    <p id="error"></p>`
                });
            } else{
                document.getElementById("error").innerText = ajax.responseText;
            }
        } else {
            Swal.fire("Error al recoger mis etiquetas", "", "error");
        }
    }
    ajax.send();
}

function newUserLabelPoint(user_id, point_id){
    var frm = document.getElementById("new-labelpoint-form");
    var formdata = new FormData(frm);
    var csrf_token = document.querySelector("meta[name = 'csrf-token']").getAttribute('content');
    formdata.append('_token', csrf_token);
    formdata.append('user_id', user_id);
    formdata.append('point_id', point_id);
    var ajax = new XMLHttpRequest();
    loading(true);
    ajax.open('POST', '/userlabel/store_point');
    ajax.onload=function(){
        loading(false);
        if(ajax.status == 200){
            console.log(ajax.responseText)
            if(ajax.responseText == "ok"){
                loadPointers('ubicaciones');
                loadDefaultData();
                openBottomContainer(false);
                Swal.fire("Etiqueta asignada correctamente", "", "success");
            } else if(ajax.responseText == "duplicated"){
                Swal.fire("La etiqueta ya estaba asignada", "", "error");
            } else{
                document.getElementById("error").innerText = ajax.responseText;
                Swal.fire("Error al asignar la etiqueta", "", "error");
            }
        }
    }
    ajax.send(formdata);
}

function removeUserLabelPoint(label_id, point_id) {
    Swal.fire({
        showConfirmButton: false,
        html:`<a id="closeModal" onclick="swal.close(); return false;">Cerrar</a><h1>Eliminar etiqueta personal</h1>
        <form onsubmit="return false;" class="crud-form">
        <p>¿Estás seguro de que quieres eliminar esta etiqueta en este punto?</p>
        <button onclick="deleteUserLabelPoint(${label_id}, ${point_id})">Eliminar</button>
        </form>`
    });
}

function deleteUserLabelPoint(label_id, point_id){
    var formdata = new FormData();
    var csrf_token = document.querySelector("meta[name = 'csrf-token']").getAttribute('content');
    formdata.append('_token', csrf_token);
    formdata.append('label_id', label_id);
    formdata.append('point_id', point_id);
    var ajax = new XMLHttpRequest();
    loading(true);
    ajax.open('POST', '/userlabel/delete_point');
    ajax.onload=function(){
        loading(false);
        if(ajax.status == 200){
            console.log(ajax.responseText)
            if(ajax.responseText == "ok"){
                loadPointers('ubicaciones');
                loadDefaultData();
                openBottomContainer(false);
                Swal.fire("Etiqueta eliminada del punto correctamente", "", "success");
            } else{
                document.getElementById("error").innerText = ajax.responseText;
            }
        }
    }
    ajax.send(formdata);
}

function removeUserLabel(label_id) {
    Swal.fire({
        showConfirmButton: false,
        html:`<a id="closeModal" onclick="swal.close(); return false;">Cerrar</a><h1>Eliminar etiqueta personal</h1>
        <form onsubmit="return false;" class="crud-form">
        <p>¿Estás seguro de que quieres eliminar esta etiqueta personal?</p>
        <button onclick="deleteUserLabel(${label_id})">Eliminar</button>
        </form>`
    });
}

function deleteUserLabel(label_id){
    var formdata = new FormData();
    var csrf_token = document.querySelector("meta[name = 'csrf-token']").getAttribute('content');
    formdata.append('_token', csrf_token);
    formdata.append('label_id', label_id);
    var ajax = new XMLHttpRequest();
    loading(true);
    ajax.open('POST', '/userlabel/delete');
    ajax.onload=function(){
        loading(false);
        if(ajax.status == 200){
            console.log(ajax.responseText)
            if(ajax.responseText == "ok"){
                loadPointers('ubicaciones');
                loadDefaultData();
                openBottomContainer(false);
                Swal.fire("Etiqueta personal eliminada correctamente", "", "success");
            } else{
                document.getElementById("error").innerText = ajax.responseText;
                Swal.fire("No se ha podido eliminar la etiqueta personal", "", "error");
            }
        } else {
            Swal.fire("No se ha podido eliminar la etiqueta personal", "", "error");
        }
    }
    ajax.send(formdata);
}

function alterLabelFilter(filter_id) {
    if (labelFilters[filter_id] == 0) labelFilters[filter_id] = 1;
    else labelFilters[filter_id] = 0;
    loadPointers('ubicaciones');

    if (labelFilters[filter_id] == 1) {
        document.getElementById('header-label-'+filter_id).classList.add('header-label-selected');
    } else {
        document.getElementById('header-label-'+filter_id).classList.remove('header-label-selected');
    }
}

function alterUserLabelFilter(filter_id) {
    if (userLabelFilters[filter_id] == 0) userLabelFilters[filter_id] = 1;
    else userLabelFilters[filter_id] = 0;
    loadPointers('ubicaciones');

    if (userLabelFilters[filter_id] == 1) {
        document.getElementById('header-user-label-'+filter_id).classList.add('header-user-label-selected');
    } else {
        document.getElementById('header-user-label-'+filter_id).classList.remove('header-user-label-selected');
    }
}
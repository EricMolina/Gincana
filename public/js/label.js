var csrf_token = document.querySelector("meta[name = 'csrf-token']").getAttribute('content');
src = document.getElementById("src");
function showLabels(){
    var labelContainer = document.getElementById("labelContainer");
    var ajax = new XMLHttpRequest();
    var formdata = new FormData();
    formdata.append('src', src.value);
    formdata.append('_token', csrf_token);
    ajax.open('POST', 'label/list');
    loading(true);
    ajax.onload = function(){
        loading(false);
        if(ajax.status == 200){
            var json = JSON.parse(ajax.responseText);
            // console.log(ajax.responseText);
            var table="<table><tr><th>Label</th><th>Color</th><th>Icono</th><th>Opciones</th></tr>";
            json.forEach(label => {
                table +=`<tr><td>${label.name}</td><td style="color:#${label.color}">#${label.color}</td><td><img class="iconsCRUD" style="filter: drop-shadow(1px 1px 5px #${label.color}) drop-shadow(1px 1px 5px #${label.color}) drop-shadow(1px 1px 5px #${label.color}) " src="../img/labels/${label.img}"></td><td><a onclick="deleteLabel(${label.id}, '${label.name}')">Borrar</a> <a onclick="openEditModal(${label.id})">Editar</a></td>
              </tr>`
                // console.log(label)
            });
            table +="</table>";
            labelContainer.innerHTML = table;
        }
    }
    ajax.send(formdata);
}
function deleteLabel(id, name){
    Swal.fire({
        title: `¿Estás seguro de que quieres eliminar la categoría ${name}?`,
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
            ajax.open('POST', 'label/delete');
            ajax.onload = function(){
                // console.log(ajax.responseText)
                if(ajax.status == 200){
                    showLabels()
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
                            title: "Label borrada correctamente"
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
function openEditModal(id){
    var formdata = new FormData();
    formdata.append('id', id);
    formdata.append('_token', csrf_token);
    var ajax = new XMLHttpRequest();
    loading(true);
    ajax.open('POST', 'label/show');
    ajax.onload = function(){
        loading(false);
        if(ajax.status == 200){
            var data = JSON.parse(ajax.responseText);
            Swal.fire({
                showConfirmButton: false,
                html:`<a id="closeModal" onclick="swal.close(); return false;">Cerrar</a><h1>Editar ${data.name}</h1>
                <form onsubmit="return false;" id="modForm" enctype="multipart/form-data" class="crud-form">
                    <input type="hidden" name="id" id="labelId" value="${data.id}">
                    <label for="name">Nombre</label>
                    <br>
                    <input type="text" name="name" id="name" value="${data.name}">
                    <br>
                    <label for="labelColor">Color</label>
                    <br>
                    <input type="color" name="labelColor" id="labelColor" value="#${data.color}">
                    <br>
                    <input type="file" id="img" name="img"/>
                    </br>
                    <img class="iconsForm" id="icon" style="filter: drop-shadow(1px 1px 5px #${data.color}) drop-shadow(1px 1px 5px #${data.color}) drop-shadow(1px 1px 5px #${data.color}) " src="../img/labels/${data.img}"
                    <br>
                    <br>
                    <button onclick="update()">Editar</button>
                </form>
                <p id="error"></p>`
            });
            var inputFile = document.getElementById("img");
            var colorInput = document.getElementById("labelColor");
            var icon =document.getElementById("icon")
            inputFile.addEventListener("change",()=>{
                // console.log("entra")
                readURL();
            })
            colorInput.addEventListener("change",()=>{
                console.log(colorInput.value)
                icon.style.filter = `drop-shadow(1px 1px 5px ${colorInput.value}) drop-shadow(1px 1px 5px ${colorInput.value}) drop-shadow(1px 1px 5px ${colorInput.value})`
            })
        }
    }
    ajax.send(formdata);
}
function readURL() {
    var preview = document.querySelector('#icon');
    var file    = document.querySelector('input[type=file]').files[0];
    var reader  = new FileReader();

    reader.onloadend = function () {
        preview.src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = "";
    }
}
function openNewForm(){
    Swal.fire({
        showConfirmButton: false,
        html:`<a id="closeModal" onclick="swal.close(); return false;">Cerrar</a><h1>Nueva categoría</h1>
        <form onsubmit="return false;" id="newForm" class="crud-form">
            <label for="name">Nombre</label>
            <br>
            <input type="text" name="name" id="name">
            <br>
            <label for="labelColor">Color</label>
            <br>
            <input type="color" name="labelColor" id="labelColor">
            <br>
            <input type="file" id="img" name="img"/>
            <br>
            <img class="iconsForm" id="icon" style="filter: drop-shadow(1px 1px 5px #000000) drop-shadow(1px 1px 5px #000000) drop-shadow(1px 1px 5px #000000) " src="">
            <br>
            <button onclick="create()">Crear</button>
        </form>
        <p id="error"></p>`
    });
    var inputFile = document.getElementById("img");
    var colorInput = document.getElementById("labelColor");
    var icon =document.getElementById("icon")
    inputFile.addEventListener("change",()=>{
        // console.log("entra")
        readURL();
    })
    colorInput.addEventListener("change",()=>{
        console.log(colorInput.value)
        icon.style.filter = `drop-shadow(1px 1px 5px ${colorInput.value}) drop-shadow(1px 1px 5px ${colorInput.value}) drop-shadow(1px 1px 5px ${colorInput.value})`
    })
}
function create(){
    var frm = document.getElementById("newForm");
    var formdata = new FormData(frm);
    formdata.append('_token', csrf_token);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', 'label/store');
    ajax.onload=function(){
        if(ajax.status == 200){
            if(ajax.responseText == "ok"){
                showLabels()
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
                    title: "Label creada correctamente"
                  });
            }else{
                document.getElementById("error").innerText = ajax.responseText;
            }
        }
    }
    ajax.send(formdata);
}
function update(){
    var frm = document.getElementById("modForm");
    // console.log(frm);
    var formdata = new FormData(frm);
    formdata.append('_token', csrf_token);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', 'label/update');
    ajax.onload=function(){
        if(ajax.status == 200){
            if(ajax.responseText == "ok"){
                showLabels()
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
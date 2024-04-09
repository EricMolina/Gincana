var csrf_token = document.querySelector("meta[name = 'csrf-token']").getAttribute('content');
src = document.getElementById("src");
function showUsers(){
    var userContainer = document.getElementById("userContainer");
    var ajax = new XMLHttpRequest();
    var formdata = new FormData();
    formdata.append('src', src.value);
    formdata.append('_token', csrf_token);
    loading(true);
    ajax.open('POST', 'user/list');
    ajax.onload = function(){
        if(ajax.status == 200){
            loading(false);
            var json = JSON.parse(ajax.responseText);
            // console.log(ajax.responseText);
            var table="<table><tr><th>Nombre</th><th>Email</th><th>Foto</th><th>Rol</th><th>Opciones</th></tr>";
            json.forEach(usr => {
                var userImg = "../img/users/"+usr.img;
                if (usr.img == null) userImg = "../img/default_user.png";
                table +=`<tr><td>${usr.name}</td><td>${usr.email}</td><td><img class="picCRUD" src="${userImg}"></td><td>${usr.role}</td><td><a onclick="deleteUsr(${usr.id})">Borrar</a> <a onclick="openEditModal(${usr.id})">Editar</a></td>
              </tr>`
                // console.log(usr)
            });
            table +="</table>";
            userContainer.innerHTML = table;
        }
    }
    ajax.send(formdata);
}

function openNewForm(){
    Swal.fire({
        showConfirmButton: false,
        html:`<a id="closeModal" onclick="swal.close(); return false;">Cerrar</a><h1>Nuevo usuario</h1>
        <form onsubmit="return false;" id="newForm" class="crud-form">
            <label for="name">Nombre</label>
            <br>
            <input type="text" name="name" id="name">
            <br>
            <label for="email">Email</label>
            <br>
            <input type="text" name="email" id="email">
            <br>
            <label for="pwd">Contraseña</label>
            <br>
            <input type="password" name="pwd" id="pwd">
            <br>
            <fieldset>
                <legend>Rol</legend>
                <input type="radio" id="user" name="rol" value="user" checked />
                <label for="user">user</label>

                <input type="radio" id="admin" name="rol" value="admin" checked />
                <label for="admin">admin</label>
            </fieldset>
            <br>
            <input type="file" id="img" name="img"/>
            </br>
            <img class="iconsForm" id="icon" src="">
            <br>
            <button onclick="create()">Crear</button>
        </form>
        <p id="error"></p>`
    });
    var inputFile = document.getElementById("img");
    var icon =document.getElementById("icon");
    inputFile.addEventListener("change",()=>{
        readURL();
    })
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
function openEditModal(id){
    var formdata = new FormData();
    formdata.append('id', id);
    formdata.append('_token', csrf_token);
    loading(true);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', 'user/show');
    ajax.onload = function(){
        loading(false);
        if(ajax.status == 200){
            var data = JSON.parse(ajax.responseText);
            var userImg = "../img/users/"+data.img;
            if (data.img == null) userImg = "../img/default_user.png";
            // console.log(data);
            Swal.fire({
                showConfirmButton: false,
                html:`<a id="closeModal" onclick="swal.close(); return false;">Cerrar</a><h1>Editando ${data.name}</h1>
                <form onsubmit="return false;" id="modForm" class="crud-form">
                    <input type="hidden" name="id" id="userId" value="${data.id}">
                    <label for="name">Nombre</label>
                    <br>
                    <input type="text" name="name" id="name" value ="${data.name}">
                    <br>
                    <label for="email">Email</label>
                    <br>
                    <input type="text" name="email" id="email" value ="${data.email}">
                    <br>
                    <label for="pwd">Contraseña</label>
                    <br>
                    <input type="password" name="pwd" id="pwd">
                    <br>
                    <fieldset id="rolField">
                        <legend>Rol</legend>
                        <input type="radio" id="user" name="rol" value="user" checked />
                        <label for="user">user</label>
        
                        <input type="radio" id="admin" name="rol" value="admin"/>
                        <label for="admin">admin</label>
                    </fieldset>
                    <br>
                    <input type="file" id="img" name="img"/>
                    <br>
                    <img class="iconsForm" id="icon" src="${userImg}">
                    <br>
                    <button onclick="update()">Editar</button>
                </form>
                <p id="error"></p>`
            });
            var rolchk = document.getElementById("rolField")
            rolchk =  rolchk.querySelectorAll("input")
            rolchk.forEach(element => {
                if(element.value == data.role){
                    element.checked = true;
                }
            });
            var inputFile = document.getElementById("img");
            var icon =document.getElementById("icon")
            inputFile.addEventListener("change",()=>{
                readURL();
            })
        }
    }
    ajax.send(formdata);
}
function update(){
    var frm = document.getElementById("modForm");
    var formdata = new FormData(frm);
    formdata.append('_token', csrf_token);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', 'user/update');
    ajax.onload=function(){
        if(ajax.status == 200){
            console.log(ajax.responseText);
            if(ajax.responseText == "ok"){
                showUsers()
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
function create(){
    var frm = document.getElementById("newForm");
    var formdata = new FormData(frm);
    formdata.append('_token', csrf_token);
    var ajax = new XMLHttpRequest();
    loading(true);
    ajax.open('POST', 'user/store');
    ajax.onload=function(){
        loading(false);
        if(ajax.status == 200){
            console.log(ajax.responseText)
            if(ajax.responseText == "ok"){
                showUsers()
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
                    title: "Usuario creado correctamente"
                  });
            }else{
                document.getElementById("error").innerText = ajax.responseText;
            }
        }
    }
    ajax.send(formdata);
}
function deleteUsr(id){
    Swal.fire({
        title: `Seguro que quieres eliminar este usuario?`,
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
            loading(true);
            ajax.open('POST', 'user/delete');
            ajax.onload = function(){
                loading(false);
                if(ajax.status == 200){
                    // console.log(JSON.parse(ajax.responseText))
                    console.log(ajax.responseText)
                    if(ajax.responseText == "ok"){
                        showUsers();
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: true,
                          });
                          Toast.fire({
                            icon: "success",
                            title: "Usuario eliminado correctamente"
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
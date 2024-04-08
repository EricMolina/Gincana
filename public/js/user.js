var csrf_token = document.querySelector("meta[name = 'csrf-token']").getAttribute('content');
src = document.getElementById("src");
function showUsers(){
    var userContainer = document.getElementById("userContainer");
    var ajax = new XMLHttpRequest();
    var formdata = new FormData();
    formdata.append('src', src.value);
    formdata.append('_token', csrf_token);
    ajax.open('POST', 'user/list');
    ajax.onload = function(){
        if(ajax.status == 200){
            var json = JSON.parse(ajax.responseText);
            console.log(ajax.responseText);
            var table="<table><tr><th>Nombre</th><th>Email</th><th>Rol</th><th>Opciones</th></tr>";
            json.forEach(usr => {
                table +=`<tr><td>${usr.name}</td><td>${usr.email}</td><td>${usr.role}</td><td><a onclick="deleteusr(${usr.id})">Borrar</a> <a onclick="openEditModal(${usr.id})">Editar</a></td>
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
        html:`<a id="closeModal" onclick="swal.close(); return false;">x</a><h1>Nueva categoría</h1>
        <form id="newForm">
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
            <br>
        </form>
        <p id="error"></p>
        <button onclick="create()">Crear</button>`
    });
}
function create(){
    var frm = document.getElementById("newForm");
    var formdata = new FormData(frm);
    formdata.append('_token', csrf_token);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', 'user/store');
    ajax.onload=function(){
        if(ajax.status == 200){
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
                    title: "Categoría creada correctamente"
                  });
            }else{
                document.getElementById("error").innerText = ajax.responseText;
            }
        }
    }
    ajax.send(formdata);
}
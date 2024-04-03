var csrf_token = document.querySelector("meta[name = 'csrf-token']").getAttribute('content');
src = document.getElementById("src");
function showLabels(){
    var labelContainer = document.getElementById("labelContainer");
    var ajax = new XMLHttpRequest();
    var formdata = new FormData();
    formdata.append('src', src.value);
    formdata.append('_token', csrf_token);
    ajax.open('POST', '/admin/label/list');
    ajax.onload = function(){
        if(ajax.status == 200){
            var json = JSON.parse(ajax.responseText);
            // console.log(ajax.responseText);
            var table="<table><tr><th>Categoría</th><th>Color</th><th>Opciones</th></tr>";
            json.forEach(label => {
                table +=`<tr><td>${label.name}</td><td style="color:#${label.color}">#${label.color}</td><td><a onclick="deleteLabel(${label.id})">Borrar</a> <a onclick="openEditModal(${label.id})">Editar</a></td>
              </tr>`
                // console.log(label)
            });
            table +="</table>";
            labelContainer.innerHTML = table;
        }
    }
    ajax.send(formdata);
}
function deleteLabel(id,name){
    Swal.fire({
        title: `Seguro que quieres eliminar la categoría?`,
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
            ajax.open('POST', '/admin/label/delete');
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
                            title: "Categoría borrada correctamente"
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
    ajax.open('POST', '/admin/label/show');
    ajax.onload = function(){
        if(ajax.status == 200){
            var data = JSON.parse(ajax.responseText);
            Swal.fire({
                showConfirmButton: false,
                html:`<a id="closeModal" onclick="swal.close(); return false;">x</a><h1>Editando ${data.name}</h1>
                <form id="modForm">
                    <input type="hidden" name="id" id="labelId" value="${data.id}">
                    <label for="name">Nombre</label>
                    <br>
                    <input type="text" name="name" id="name" value="${data.name}">
                    <br>
                    <label for="labelColor">Color</label>
                    <br>
                    <input type="color" name="labelColor" id="labelColor" value="#${data.color}">
                    <br>
                    <br>
                </form>
                <p id="error"></p>
                <button onclick="update()">Crear</button>`
            });
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
            <label for="labelColor">Color</label>
            <br>
            <input type="color" name="labelColor" id="labelColor">
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
    ajax.open('POST', '/admin/label/store');
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
                    title: "Categoría creada correctamente"
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
    ajax.open('POST', '/admin/label/update');
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
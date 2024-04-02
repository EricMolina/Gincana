var csrf_token = document.querySelector("meta[name = 'csrf-token']").getAttribute('content');
src = document.getElementById("src");
function showLabels(){
    var labelContainer = document.getElementById("labelContainer");
    var ajax = new XMLHttpRequest();
    var formdata = new FormData();
    formdata.append('src', src.value);
    formdata.append('_token', csrf_token);
    ajax.open('POST', '/label/list');
    ajax.onload = function(){
        if(ajax.status == 200){
            var json = JSON.parse(ajax.responseText);
            // console.log(ajax.responseText);
            var table="<table><tr><th>Categoría</th><th>Color</th><th>Opciones</th></tr>";
            json.forEach(label => {
                table +=`<tr><td>${label.name}</td><td style="color:#${label.color}">#${label.color}</td><td>Borrar Editar</td>
              </tr>`
                // console.log(label)
            });
            table +="</table>";
            labelContainer.innerHTML = table;
        }
    }
    ajax.send(formdata);
}

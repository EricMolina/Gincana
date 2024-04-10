<p onclick="swal.close()">X</p>
<h1>Nueva Gincana</h1>
<form onsubmit="return false" method="post" id="ginForm">
    @csrf
    <input type="hidden" name="coordx" id="coordx">
    <input type="hidden" name="coordy" id="coordy">
    <label for="name">Nombre</label>
    <br>
    <input type="text" name="name">
    <br>
    <label for="desc">Descripción</label>
    <br>
    <input type="text" name="desc">
    <br>
    <label for="difficulty">Dificultad</label>
    <br>
    <input type="number" name="difficulty">
    <br>
    <label for="coord">Punto de inicio</label>
    <p id="coord"></p>
    <div style="width:100%;height:50vh"id="mapNewGincana"></div>
    <button onclick="NuevoPunto()">Nuevo punto</button>
    <div id="puntos"></div>
    <button onclick="crearGin()">Crear Gincana</button>
</form>
<p id="error"></p>
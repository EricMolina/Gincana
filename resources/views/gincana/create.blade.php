<h1>Nueva Gincana</h1>
<form onsubmit="return false" method="post">
    <input type="hidden" name="coordx" id="coordx">
    <input type="hidden" name="coordy" id="coordy">
    <label for="name">Nombre</label>
    <input type="text" name="name" id="name">
    <br>
    <label for="desc">Descripción</label>
    <input type="text" name="desc" id="desc">
    <br>
    <label for="coord">Punto de inicio</label>
    <p id="coord"></p>
    <div style="width:100%;height:50vh"id="mapNewGincana"></div>
    <button onclick="NuevoPunto()">Nuevo punto</button>
    <div id="puntos"></div>
</form>
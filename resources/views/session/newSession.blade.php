<h1>Crear sesión</h1>
<form class="crud-form" onsubmit="return false" method="post" id="frm">
    @csrf
    <input type="hidden" name="gincana_id" id = "gincana_id">
    <label for="name">Nombre</label>
    <br>
    <input type="text" name="name">
    <br>
    <button onclick="createSession()">Crear sesión</button>
</form>
<p id="error"></p>

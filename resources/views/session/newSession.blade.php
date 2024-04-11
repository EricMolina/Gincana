<h1>Crear sesión</h1>
<form onsubmit="return false" method="post" id="frm">
    @csrf
    <input type="hidden" name="gincana_id" id = "gincana_id">
    <label for="name">Nombre</label>
    <br>
    <input type="text" name="name">
</form>
<p id="error"></p>
<button onclick="createSession()">Crear</button>
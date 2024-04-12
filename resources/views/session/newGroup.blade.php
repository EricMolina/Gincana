<h1>Crear grupo</h1>
<form class="crud-form" onsubmit="return false" method="post" id="frm">
    @csrf
    <input type="hidden" name="session_id" id = "session_id">
    <label for="name">Nombre</label>
    <br>
    <input type="text" name="name">
    <br>
    <button onclick="createGroup()">Crear grupo</button>
</form>
<p id="error"></p>

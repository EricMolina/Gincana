<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Admin - Users</title>
</head>
<body>
    <h1>Users</h1>
    <a onclick="openNewForm()">Nuevo</a>
    <input type="text" name="src" id="src">
    <div id="userContainer"></div>
    <script src="{{asset('js/user.js')}}"></script>
    <script>
        src.addEventListener("keyup",()=>{showUsers();});
        window.onload = showUsers();
    </script>
</body>
</html>

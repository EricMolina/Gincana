<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    
    <form action="{{ route('api.groups.join') }}" method="POST">
        @csrf
        <input type="number" name="gincana_session_group_id" placeholder="gincana_session_group_id"><br>
        <button type="submit">Enviar</button>
    </form>

</body>
</html>
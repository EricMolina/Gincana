<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Labels</title>
</head>
<body>
    <h1>Labels</h1>
    <a href="{{route('label.create')}}">Nuevo</a>
    <input type="text" name="src" id="src">
    <div id="labelContainer"></div>
    <script src="{{asset('js/label.js')}}"></script>
    <script>
        src.addEventListener("keyup",()=>{showLabels();});
        window.onload = showLabels();
    </script>
</body>
</html>
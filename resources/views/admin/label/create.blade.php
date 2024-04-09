<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Nueva categoría</h1>
    <form action="{{route('label.store')}}" method="post">
        @csrf
        <label for="name">Nombre</label>
        <input type="text" name="name" id="name">
        <br>
        <label for="labelColor">Color</label>
        <input type="color" name="labelColor" id="labelColor" value="#ffffff">
        {{-- <label for="hexColor">Codigo hexadecimal</label>
        <input type="text" name="hexColor" id="hexColor"> --}}
        <br>
        <button type="submit">Crear</button>
    </form>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <a href="{{route('label.index')}}">Volver</a>
    {{-- <script src="{{asset('js/colorpicker.js')}}"></script> --}}
</body>
</html>
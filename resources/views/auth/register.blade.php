@extends('layouts.basic')
    
@section('title', 'Foot Track')

@section('content')
<form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="loginForm">
    <h1 class="font-medium">Crea una cuenta en Foot Track</h1>
    @csrf
    <label class="font-medium" for="name" class="roboto-black">Nombre de usuario</label>
    <br>
    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror logininput register" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
    @error('name')
        <span class="invalid-feedback" role="alert">
            <strong class="errorMsg">{{ $message }}</strong>
        </span>
    @enderror
    <br>
    <label class="font-medium" for="email" class="roboto-black">Correo electrónico</label>
    <br>
    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror logininput register" name="email" value="{{ old('email') }}" required autocomplete="email">
    @error('email')
        <span class="invalid-feedback" role="alert">
            <strong class="errorMsg">{{ $message }}</strong>
        </span>
    @enderror
    <br>
    <label class="font-medium" for="password" class="roboto-black">Contraseña</label>
    <br>
    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror logininput register" name="password" required autocomplete="new-password">
    @error('password')
        <span class="invalid-feedback" role="alert">
            <strong class="errorMsg">{{ $message }}</strong>
        </span>
    @enderror
    <br>
    <label class="font-medium" for="password-confirm" class="roboto-black">Repite la contraseña</label>
    <br>
    <input id="password-confirm" type="password" class="form-control logininput register" name="password_confirmation" required autocomplete="new-password">
    <br>
    <button class="font-bold" type="submit" id="loginbtn">Crear cuenta</button>
    <br>
    <a class="font-medium" href="/login">¿Ya tienes cuenta? ¡Haz clic aquí!</a>
</form>
@endsection
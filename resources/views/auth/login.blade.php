<form method="POST" action="{{ route('login') }}" id="loginForm">
    @csrf
    <label for="email" class="col-md-4 col-form-label text-md-end roboto-black">Correo electrónico</label>

    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror logininput" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

    <br>
    <label for="password" class="col-md-4 col-form-label text-md-end roboto-black">Contraseña</label>

    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror logininput" name="password" required autocomplete="current-password">

    @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
    @error('email')
    <span class="invalid-feedback roboto-black" role="alert">
        <strong class="errorMsg">{{ $message }}</strong>
    </span>
    @enderror
    <br>
    <button type="submit" class="btn btn-primary" id="loginbtn">
        Iniciar sesión
    </button>

    @if (Route::has('password.request'))
        <a class="btn btn-link" href="{{ route('password.request') }}">
            {{ __('Forgot Your Password?') }}
        </a>
    @endif
    <br>
</form>
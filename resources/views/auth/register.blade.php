<form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="loginForm">
    @csrf
    <div class="row">
        <div class="column-3">
            <label for="name" class="roboto-black">Nombre de usuario</label>
            <br>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror logininput register" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong class="errorMsg">{{ $message }}</strong>
                </span>
            @enderror
            <br>
            <label for="email" class="roboto-black">Correo electrónico</label>
            <br>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror logininput register" name="email" value="{{ old('email') }}" required autocomplete="email">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong class="errorMsg">{{ $message }}</strong>
                </span>
            @enderror
            <br>
        </div>
        <div class="column-3">
            <label for="password" class="roboto-black">Contraseña</label>
            <br>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror logininput register" name="password" required autocomplete="new-password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong class="errorMsg">{{ $message }}</strong>
                </span>
            @enderror
            <br>
            <label for="password-confirm" class="roboto-black">Repite la contraseña</label>
            <br>
            <input id="password-confirm" type="password" class="form-control logininput register" name="password_confirmation" required autocomplete="new-password">
            <br>
        </div>
        <div class="column-3">
           {{--  <label for="profile_image" class="roboto-black profileText">Imagen de perfil</label>
            <input id="profile_image" type="file" class="form-control @error('profile_image') is-invalid @enderror" name="profile_image" accept="image/*">
            @error('profile_image')
                <span class="invalid-feedback" role="alert">
                    <strong class="errorMsg">{{ $message }}</strong>
                </span>
            @enderror
            <div id="imgContainer">
                <img src="" id="profilePreview" alt="" srcset="" height="100px" width="100px">
            </div>
        </div> --}}
        <button type="submit" id="loginbtn">
            Registrarse
        </button>
    </div>
</form>
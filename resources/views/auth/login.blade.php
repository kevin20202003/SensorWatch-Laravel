@extends('layouts.app')

@section('content')
    <div class="row justify-content-center py-5">
        <div class="col-md-8 col-lg-5">
            <div class="glass-card p-4 p-lg-5">
                <h1 class="h3 fw-bold mb-2">Ingresar</h1>
                <p class="text-soft mb-4">Accede con tu correo electrónico y contraseña.</p>

                <form method="POST" action="{{ route('login.perform') }}" class="d-grid gap-3">
                    @csrf
                    <div>
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="correo_electronico" value="{{ old('correo_electronico') }}" class="form-control form-control-lg bg-black bg-opacity-25 text-white border-white border-opacity-10" required>
                    </div>
                    <div>
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control form-control-lg bg-black bg-opacity-25 text-white border-white border-opacity-10" required>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Recordarme</label>
                    </div>
                    <button class="btn btn-warning btn-lg" type="submit">Entrar</button>
                    <p class="mb-0 text-soft">¿No tienes cuenta? <a href="{{ route('register') }}" class="text-warning">Regístrate</a></p>
                </form>
            </div>
        </div>
    </div>
@endsection
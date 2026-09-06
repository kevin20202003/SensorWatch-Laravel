@extends('layouts.app')

@section('content')
    <div class="row justify-content-center py-5">
        <div class="col-md-10 col-lg-6">
            <div class="glass-card p-4 p-lg-5">
                <h1 class="h3 fw-bold mb-2">Crear cuenta</h1>
                <p class="text-soft mb-4">Registra un nuevo usuario para monitorear SensorWatch.</p>

                <form method="POST" action="{{ route('register.perform') }}" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control form-control-lg bg-black bg-opacity-25 text-white border-white border-opacity-10" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="correo_electronico" value="{{ old('correo_electronico') }}" class="form-control form-control-lg bg-black bg-opacity-25 text-white border-white border-opacity-10" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control form-control-lg bg-black bg-opacity-25 text-white border-white border-opacity-10" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control form-control-lg bg-black bg-opacity-25 text-white border-white border-opacity-10" required>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-warning btn-lg" type="submit">Registrar y entrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
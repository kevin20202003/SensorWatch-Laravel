@extends('layouts.app')

@section('content')
    <div class="row justify-content-center py-5">
        <div class="col-md-8 col-lg-5">
            <div class="glass-card p-4 p-lg-5">
                <h1 class="h3 fw-bold mb-2">Verificación en dos pasos</h1>
                <p class="text-soft mb-4">Ingresa el código de 6 dígitos que enviamos a <strong>{{ $correo_electronico }}</strong>.</p>

                @if (session('status'))
                    <div class="alert alert-success border-0 shadow-sm mb-3">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.verify') }}" class="d-grid gap-3">
                    @csrf
                    <div>
                        <label class="form-label">Código de verificación</label>
                        <input type="text" name="codigo_verificacion" maxlength="6" class="form-control form-control-lg bg-black bg-opacity-25 text-white border-white border-opacity-10 text-center" required>
                    </div>

                    @error('codigo_verificacion')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror

                    <button class="btn btn-warning btn-lg" type="submit">Verificar</button>

                    <div class="d-flex justify-content-between gap-2">
                        <a href="{{ route('login') }}" class="text-warning">Volver al inicio</a>
                        <button type="submit" formaction="{{ route('login.resend') }}" class="btn btn-link p-0 text-warning">Reenviar código</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

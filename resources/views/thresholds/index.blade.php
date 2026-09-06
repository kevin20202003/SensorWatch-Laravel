@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
        <div>
            <h1 class="fw-bold mb-2">Umbrales</h1>
            <p class="text-soft mb-0">Define alertas para suelo, ambiente y clima por usuario.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-light">Volver al dashboard</a>
    </div>

    <div class="row g-4">
        @foreach ([
            'suelo' => ['title' => 'Umbral de suelo', 'showPressure' => false],
            'ambiente' => ['title' => 'Umbral de ambiente', 'showPressure' => false],
            'clima' => ['title' => 'Umbral meteorológico', 'showPressure' => true],
        ] as $key => $meta)
            <div class="col-lg-4">
                <div class="glass-card p-4 h-100">
                    <h2 class="h5 fw-semibold mb-3">{{ $meta['title'] }}</h2>
                    <form method="POST" action="{{ route('umbrales.store') }}" class="d-grid gap-3">
                        @csrf
                        <input type="hidden" name="type" value="{{ $key }}">
                        <div>
                            <label class="form-label">Humedad mínima</label>
                            <input type="number" step="0.01" name="humedad_min" class="form-control bg-black bg-opacity-25 text-white border-white border-opacity-10" required>
                        </div>
                        <div>
                            <label class="form-label">Humedad máxima</label>
                            <input type="number" step="0.01" name="humedad_max" class="form-control bg-black bg-opacity-25 text-white border-white border-opacity-10" required>
                        </div>
                        <div>
                            <label class="form-label">Temperatura mínima</label>
                            <input type="number" step="0.01" name="temperatura_min" class="form-control bg-black bg-opacity-25 text-white border-white border-opacity-10" required>
                        </div>
                        <div>
                            <label class="form-label">Temperatura máxima</label>
                            <input type="number" step="0.01" name="temperatura_max" class="form-control bg-black bg-opacity-25 text-white border-white border-opacity-10" required>
                        </div>
                        @if ($meta['showPressure'])
                            <div>
                                <label class="form-label">Presión mínima</label>
                                <input type="number" step="0.01" name="presion_min" class="form-control bg-black bg-opacity-25 text-white border-white border-opacity-10" required>
                            </div>
                            <div>
                                <label class="form-label">Presión máxima</label>
                                <input type="number" step="0.01" name="presion_max" class="form-control bg-black bg-opacity-25 text-white border-white border-opacity-10" required>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-warning">Guardar</button>
                    </form>
                    @php($current = $thresholds[$key])
                    <div class="mt-4 text-soft small">
                        <div class="fw-semibold text-white mb-1">Actual</div>
                        @if ($current)
                            <div>Humedad: {{ $current->humedad_min }} - {{ $current->humedad_max }}</div>
                            <div>Temperatura: {{ $current->temperatura_min }} - {{ $current->temperatura_max }}</div>
                            @if ($meta['showPressure'])
                                <div>Presión: {{ $current->presion_min }} - {{ $current->presion_max }}</div>
                            @endif
                        @else
                            <div>No hay umbrales configurados todavía.</div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
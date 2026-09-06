@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
        <div>
            <h1 class="fw-bold mb-2">Bienvenido, {{ auth()->user()->nombre }}</h1>
            <p class="text-soft mb-0">Resumen operativo de SensorWatch con las últimas lecturas y umbrales guardados.</p>
        </div>
        <a href="{{ route('umbrales.index') }}" class="btn btn-warning">Administrar umbrales</a>
    </div>

    <div class="row g-4 mb-4">
        @foreach ($panels as $panel)
            <div class="col-md-4">
                <a href="{{ $panel['route'] }}" class="text-decoration-none text-reset">
                    <div class="glass-card p-4 h-100">
                        <div class="small text-uppercase text-soft mb-2">{{ $panel['title'] }}</div>
                        <div class="display-6 fw-semibold mb-2">{{ $panel['value'] }}</div>
                        <div class="text-soft">{{ $panel['subtitle'] }}</div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="glass-card p-4 h-100">
                <h2 class="h5 fw-semibold mb-3">Últimas lecturas</h2>
                <div class="table-responsive">
                    <table class="table table-darkish table-sm align-middle mb-0">
                        <tbody>
                        <tr>
                            <th>Suelo</th>
                            <td>{{ $soil ? number_format((float) $soil->humedad, 1) . ' %' : 'Sin datos' }}</td>
                            <td>{{ $soil ? number_format((float) $soil->temperatura, 1) . ' °C' : '' }}</td>
                            <td>{{ $soil ? number_format((float) $soil->PH, 2) : '' }}</td>
                        </tr>
                        <tr>
                            <th>Ambiente</th>
                            <td>{{ $environment ? number_format((float) $environment->humedad_amb, 1) . ' %' : 'Sin datos' }}</td>
                            <td>{{ $environment ? number_format((float) $environment->temperatura_amb, 1) . ' °C' : '' }}</td>
                            <td>{{ $environment ? number_format((float) $environment->lux, 0) . ' lux' : '' }}</td>
                        </tr>
                        <tr>
                            <th>Clima</th>
                            <td>{{ $weather ? number_format((float) $weather->humidity, 0) . ' %' : 'Sin datos' }}</td>
                            <td>{{ $weather ? number_format((float) $weather->temp, 1) . ' °C' : '' }}</td>
                            <td>{{ $weather ? number_format((float) $weather->pressure, 0) . ' hPa' : '' }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="glass-card p-4 h-100">
                <h2 class="h5 fw-semibold mb-3">Umbrales activos</h2>
                <div class="d-grid gap-3">
                    <div class="p-3 rounded-4 bg-black bg-opacity-25 border border-white border-opacity-10">
                        <div class="fw-semibold mb-1">Suelo</div>
                        <div class="text-soft">
                            {{ $soilThreshold ? "Humedad {$soilThreshold->humedad_min} - {$soilThreshold->humedad_max}, temperatura {$soilThreshold->temperatura_min} - {$soilThreshold->temperatura_max}" : 'Sin umbrales configurados' }}
                        </div>
                    </div>
                    <div class="p-3 rounded-4 bg-black bg-opacity-25 border border-white border-opacity-10">
                        <div class="fw-semibold mb-1">Ambiente</div>
                        <div class="text-soft">
                            {{ $environmentThreshold ? "Humedad {$environmentThreshold->humedad_min} - {$environmentThreshold->humedad_max}, temperatura {$environmentThreshold->temperatura_min} - {$environmentThreshold->temperatura_max}" : 'Sin umbrales configurados' }}
                        </div>
                    </div>
                    <div class="p-3 rounded-4 bg-black bg-opacity-25 border border-white border-opacity-10">
                        <div class="fw-semibold mb-1">Clima</div>
                        <div class="text-soft">
                            {{ $weatherThreshold ? "Humedad {$weatherThreshold->humedad_min} - {$weatherThreshold->humedad_max}, temperatura {$weatherThreshold->temperatura_min} - {$weatherThreshold->temperatura_max}, presión {$weatherThreshold->presion_min} - {$weatherThreshold->presion_max}" : 'Sin umbrales configurados' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="glass-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 fw-semibold mb-0">Notificaciones recientes</h2>
                    <span class="badge text-bg-light text-dark">{{ $notifications->count() }}</span>
                </div>
                <div class="row g-3">
                    @forelse ($notifications as $notification)
                        <div class="col-md-6 col-xl-4">
                            <div class="p-3 rounded-4 bg-black bg-opacity-25 border border-white border-opacity-10 h-100">
                                <div class="small text-soft mb-2">{{ $notification->fecha }}</div>
                                <div>{{ $notification->mensaje }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-soft">No tienes notificaciones pendientes.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
        <div>
            <h1 class="fw-bold mb-2">{{ $config['title'] }}</h1>
            <p class="text-soft mb-0">Historía reciente, métricas actuales y evolución de lecturas.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-light">Volver al dashboard</a>
    </div>

    <div class="row g-4 mb-4">
        @foreach ($metrics as $metric)
            <div class="col-md-4">
                <div class="glass-card p-4 h-100">
                    <div class="text-soft small text-uppercase mb-2">{{ $metric['label'] }}</div>
                    <div class="display-6 fw-semibold">
                        {{ is_null($metric['value']) ? 'Sin datos' : number_format((float) $metric['value'], 2) . $metric['suffix'] }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="glass-card p-4">
                <h2 class="h5 fw-semibold mb-3">Tendencia reciente</h2>
                <canvas id="sensorChart" height="110"></canvas>
            </div>
        </div>

        <div class="col-12">
            <div class="glass-card p-4">
                <h2 class="h5 fw-semibold mb-3">Últimos registros</h2>
                <div class="table-responsive">
                    <table class="table table-darkish table-hover align-middle mb-0">
                        <thead>
                        <tr>
                            <th>Fecha</th>
                            @foreach ($config['metrics'] as $metric)
                                <th>{{ $metric['label'] }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($rows as $row)
                            <tr>
                                <td>{{ data_get($row, $config['date_field']) }}</td>
                                @foreach ($config['metrics'] as $metric)
                                    <td>{{ number_format((float) data_get($row, $metric['field']), 2) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        const chartData = @json($chart);
        const ctx = document.getElementById('sensorChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: chartData.datasets.map((dataset) => ({
                    ...dataset,
                    fill: false,
                    tension: 0.35,
                    borderWidth: 2,
                    pointRadius: 2,
                }))
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: '#e2e8f0'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#cbd5e1' },
                        grid: { color: 'rgba(255,255,255,.06)' }
                    },
                    y: {
                        ticks: { color: '#cbd5e1' },
                        grid: { color: 'rgba(255,255,255,.06)' }
                    }
                }
            }
        });
    </script>
@endpush
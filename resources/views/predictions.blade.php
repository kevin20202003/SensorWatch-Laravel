@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
        <div>
            <h1 class="fw-bold mb-2">Predicciones</h1>
            <p class="text-soft mb-0">Resultados históricos de los modelos de predicción del proyecto original.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-light">Volver al dashboard</a>
    </div>

    <div class="row g-4">
        @foreach ($predictions as $section)
            <div class="col-12">
                <div class="glass-card p-4">
                    <h2 class="h5 fw-semibold mb-3">{{ $section['title'] }}</h2>
                    <div class="table-responsive">
                        <table class="table table-darkish table-hover align-middle mb-0">
                            <thead>
                            <tr>
                                <th>Fecha</th>
                                @foreach ($section['columns'] as $column)
                                    <th>{{ $column['label'] }}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($section['rows'] as $row)
                                <tr>
                                    <td>{{ data_get($row, $section['date_field']) }}</td>
                                    @foreach ($section['columns'] as $column)
                                        <td>{{ number_format((float) data_get($row, $column['field']), 2) }}</td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($section['columns']) + 1 }}" class="text-soft">No hay registros de predicción todavía.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
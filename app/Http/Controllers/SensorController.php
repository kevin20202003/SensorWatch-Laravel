<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SensorController extends Controller
{
    public function show(string $section): View
    {
        $configs = [
            'suelo' => [
                'title' => 'Sensor de Suelo',
                'table' => 'datos_suelo',
                'order' => 'id',
                'date_field' => 'created_at',
                'metrics' => [
                    ['field' => 'humedad', 'label' => 'Humedad', 'color' => '#2563eb', 'suffix' => ' %'],
                    ['field' => 'temperatura', 'label' => 'Temperatura', 'color' => '#f97316', 'suffix' => ' °C'],
                    ['field' => 'PH', 'label' => 'pH', 'color' => '#16a34a', 'suffix' => ''],
                ],
            ],
            'ambiente' => [
                'title' => 'Sensor de Ambiente',
                'table' => 'datos_ambiente',
                'order' => 'id',
                'date_field' => 'created_at',
                'metrics' => [
                    ['field' => 'humedad_amb', 'label' => 'Humedad', 'color' => '#0ea5e9', 'suffix' => ' %'],
                    ['field' => 'temperatura_amb', 'label' => 'Temperatura', 'color' => '#f97316', 'suffix' => ' °C'],
                    ['field' => 'lux', 'label' => 'Lux', 'color' => '#7c3aed', 'suffix' => ''],
                ],
            ],
            'clima' => [
                'title' => 'Datos Meteorológicos',
                'table' => 'datos_meteorologicos',
                'order' => 'date',
                'date_field' => 'date',
                'metrics' => [
                    ['field' => 'temp', 'label' => 'Temperatura', 'color' => '#f97316', 'suffix' => ' °C'],
                    ['field' => 'humidity', 'label' => 'Humedad', 'color' => '#0ea5e9', 'suffix' => ' %'],
                    ['field' => 'pressure', 'label' => 'Presión', 'color' => '#14b8a6', 'suffix' => ' hPa'],
                    ['field' => 'wind_speed', 'label' => 'Viento', 'color' => '#8b5cf6', 'suffix' => ''],
                ],
            ],
        ];

        abort_unless(isset($configs[$section]), 404);

        $config = $configs[$section];
        $rows = DB::table($config['table'])->orderByDesc($config['order'])->limit(12)->get()->reverse()->values();
        $latest = $rows->last();

        $metrics = collect($config['metrics'])->map(function (array $metric) use ($latest) {
            $value = $latest ? data_get($latest, $metric['field']) : null;

            return [
                'label' => $metric['label'],
                'value' => $value,
                'suffix' => $metric['suffix'],
            ];
        })->all();

        $chart = [
            'labels' => $rows->map(function ($row) use ($config) {
                return Carbon::parse(data_get($row, $config['date_field']))->format('d/m H:i');
            })->all(),
            'datasets' => collect($config['metrics'])->map(function (array $metric) use ($rows) {
                return [
                    'label' => $metric['label'],
                    'data' => $rows->map(function ($row) use ($metric) {
                        return (float) (data_get($row, $metric['field']) ?? 0);
                    })->all(),
                    'borderColor' => $metric['color'],
                    'backgroundColor' => $metric['color'],
                ];
            })->all(),
        ];

        return view('sensors.show', compact('config', 'rows', 'latest', 'metrics', 'chart'));
    }

    public function predictions(): View
    {
        $sections = [
            [
                'title' => 'Predicción de Suelo',
                'table' => 'datos_suelo_predicciones',
                'order' => 'created_at',
                'date_field' => 'created_at',
                'columns' => [
                    ['field' => 'humedad', 'label' => 'Humedad'],
                    ['field' => 'temperatura', 'label' => 'Temperatura'],
                    ['field' => 'ph', 'label' => 'pH'],
                ],
            ],
            [
                'title' => 'Predicción de Ambiente',
                'table' => 'datos_ambiente_predicciones',
                'order' => 'created_at',
                'date_field' => 'created_at',
                'columns' => [
                    ['field' => 'humedad_amb', 'label' => 'Humedad'],
                    ['field' => 'temperatura_amb', 'label' => 'Temperatura'],
                    ['field' => 'lux', 'label' => 'Lux'],
                ],
            ],
            [
                'title' => 'Predicción Meteorológica',
                'table' => 'datos_meteorologicos_predicciones',
                'order' => 'date',
                'date_field' => 'date',
                'columns' => [
                    ['field' => 'temp', 'label' => 'Temperatura'],
                    ['field' => 'humidity', 'label' => 'Humedad'],
                    ['field' => 'pressure', 'label' => 'Presión'],
                    ['field' => 'wind_speed', 'label' => 'Viento'],
                ],
            ],
        ];

        $predictions = collect($sections)->map(function (array $section) {
            $rows = DB::table($section['table'])->orderByDesc($section['order'])->limit(8)->get()->reverse()->values();

            return [
                'title' => $section['title'],
                'date_field' => $section['date_field'],
                'columns' => $section['columns'],
                'rows' => $rows,
            ];
        })->all();

        return view('predictions', compact('predictions'));
    }

    public function thresholds(): View
    {
        $userId = Auth::id();

        $thresholds = [
            'suelo' => DB::table('umbral_suelo')->where('id_usuario', $userId)->orderByDesc('id')->first(),
            'ambiente' => DB::table('umbral_ambiente')->where('id_usuario', $userId)->orderByDesc('id')->first(),
            'clima' => DB::table('umbral_meteorologicos')->where('id_usuario', $userId)->orderByDesc('id')->first(),
        ];

        return view('thresholds.index', compact('thresholds'));
    }

    public function storeThresholds(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'in:suelo,ambiente,clima'],
            'humedad_min' => ['required', 'numeric'],
            'humedad_max' => ['required', 'numeric'],
            'temperatura_min' => ['required', 'numeric'],
            'temperatura_max' => ['required', 'numeric'],
            'presion_min' => ['nullable', 'numeric'],
            'presion_max' => ['nullable', 'numeric'],
        ]);

        $payload = [
            'id_usuario' => Auth::id(),
            'humedad_min' => $data['humedad_min'],
            'humedad_max' => $data['humedad_max'],
            'temperatura_min' => $data['temperatura_min'],
            'temperatura_max' => $data['temperatura_max'],
            'fecha' => now(),
        ];

        if ($data['type'] === 'suelo') {
            DB::table('umbral_suelo')->insert($payload);
        } elseif ($data['type'] === 'ambiente') {
            DB::table('umbral_ambiente')->insert($payload);
        } else {
            $payload['presion_min'] = $data['presion_min'];
            $payload['presion_max'] = $data['presion_max'];
            DB::table('umbral_meteorologicos')->insert($payload);
        }

        return redirect()->route('umbrales.index')->with('status', 'Umbrales guardados correctamente.');
    }
}

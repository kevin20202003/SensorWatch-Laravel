<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();

        $soil = DB::table('datos_suelo')->orderByDesc('id')->first();
        $environment = DB::table('datos_ambiente')->orderByDesc('id')->first();
        $weather = DB::table('datos_meteorologicos')->orderByDesc('date')->first();

        $soilThreshold = DB::table('umbral_suelo')->where('id_usuario', $userId)->orderByDesc('id')->first();
        $environmentThreshold = DB::table('umbral_ambiente')->where('id_usuario', $userId)->orderByDesc('id')->first();
        $weatherThreshold = DB::table('umbral_meteorologicos')->where('id_usuario', $userId)->orderByDesc('id')->first();

        $notifications = DB::table('notificaciones')
            ->where('id_usuario', $userId)
            ->where('leida', 0)
            ->orderByDesc('fecha')
            ->limit(5)
            ->get();

        $panels = [
            [
                'title' => 'Sensor de suelo',
                'route' => route('sensores.show', 'suelo'),
                'value' => $soil ? number_format((float) $soil->humedad, 1) . ' %' : 'Sin datos',
                'subtitle' => $soil ? 'Humedad actual' : 'No hay lecturas recientes',
            ],
            [
                'title' => 'Ambiente',
                'route' => route('sensores.show', 'ambiente'),
                'value' => $environment ? number_format((float) $environment->temperatura_amb, 1) . ' °C' : 'Sin datos',
                'subtitle' => $environment ? 'Temperatura ambiente' : 'No hay lecturas recientes',
            ],
            [
                'title' => 'Clima',
                'route' => route('sensores.show', 'clima'),
                'value' => $weather ? number_format((float) $weather->temp, 1) . ' °C' : 'Sin datos',
                'subtitle' => $weather ? 'Temperatura meteorológica' : 'No hay lecturas recientes',
            ],
        ];

        return view('dashboard', compact(
            'panels',
            'soil',
            'environment',
            'weather',
            'soilThreshold',
            'environmentThreshold',
            'weatherThreshold',
            'notifications'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        $notifications = DB::table('notificaciones')
            ->where('id_usuario', Auth::id())
            ->orderByDesc('fecha')
            ->get();

        return response()->json([
            'num_notificaciones' => $notifications->count(),
            'notificaciones' => $notifications,
        ]);
    }

    public function destroy(Request $request, int $notification): JsonResponse
    {
        $deleted = DB::table('notificaciones')
            ->where('id', $notification)
            ->where('id_usuario', Auth::id())
            ->delete();

        return response()->json([
            'success' => $deleted > 0,
        ]);
    }
}

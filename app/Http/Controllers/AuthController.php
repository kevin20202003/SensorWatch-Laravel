<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'correo_electronico' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt([
            'correo_electronico' => $credentials['correo_electronico'],
            'password' => $credentials['password'],
            'estado' => 'Activo',
        ], $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        throw ValidationException::withMessages([
            'correo_electronico' => 'Las credenciales no coinciden o la cuenta está inactiva.',
        ]);
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'correo_electronico' => ['required', 'email', 'max:255', 'unique:usuarios,correo_electronico'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'nombre' => $data['nombre'],
            'correo_electronico' => $data['correo_electronico'],
            'password' => $data['password'],
            'estado' => 'Activo',
            'codigo_verificacion' => null,
            'codigo_timestamp' => null,
            'intentos_fallidos' => 0,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function showChangePassword(): View
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        /** @var User $user */
        $user = $request->user();

        if (! Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'La contraseña actual es incorrecta.',
            ]);
        }

        $user->password = $data['password'];
        $user->save();

        return redirect()->route('dashboard')->with('status', 'La contraseña se actualizó correctamente.');
    }
}

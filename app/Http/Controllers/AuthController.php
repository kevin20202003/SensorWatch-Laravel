<?php

namespace App\Http\Controllers;

use App\Mail\TwoFactorCodeMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

        $user = User::where('correo_electronico', $credentials['correo_electronico'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password) || $user->estado !== 'Activo') {
            throw ValidationException::withMessages([
                'correo_electronico' => 'Las credenciales no coinciden o la cuenta está inactiva.',
            ]);
        }

        $this->sendVerificationCode($user);

        $request->session()->put('two_factor_user_id', $user->id_usuario);
        $request->session()->put('two_factor_remember', $request->boolean('remember'));

        return redirect()->route('login.verify.form')->with('status', 'Hemos enviado un código de verificación a tu correo de Gmail.');
    }

    public function showVerificationForm(): View|RedirectResponse
    {
        $userId = session('two_factor_user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (! $user) {
            session()->forget(['two_factor_user_id', 'two_factor_remember']);

            return redirect()->route('login');
        }

        return view('auth.verify-code', [
            'correo_electronico' => $user->correo_electronico,
        ]);
    }

    public function verifyCode(Request $request): RedirectResponse
    {
        $request->validate([
            'codigo_verificacion' => ['required', 'string', 'size:6'],
        ]);

        $userId = $request->session()->get('two_factor_user_id');
        $user = User::find($userId);

        if (! $user) {
            throw ValidationException::withMessages([
                'codigo_verificacion' => 'La sesión de verificación ha expirado.',
            ]);
        }

        $expiresAt = $user->codigo_timestamp ? $user->codigo_timestamp->copy()->addMinutes(5) : null;

        if (
            ! $user->codigo_verificacion ||
            ! $user->codigo_timestamp ||
            $expiresAt->isPast() ||
            ! hash_equals((string) $user->codigo_verificacion, (string) $request->codigo_verificacion)
        ) {
            throw ValidationException::withMessages([
                'codigo_verificacion' => 'El código es incorrecto o ha expirado.',
            ]);
        }

        $user->codigo_verificacion = null;
        $user->codigo_timestamp = null;
        $user->save();

        Auth::login($user, $request->session()->get('two_factor_remember', false));
        $request->session()->regenerate();
        $request->session()->forget(['two_factor_user_id', 'two_factor_remember']);

        return redirect()->route('dashboard');
    }

    public function resendCode(Request $request): RedirectResponse
    {
        $userId = $request->session()->get('two_factor_user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (! $user) {
            return redirect()->route('login');
        }

        $this->sendVerificationCode($user);

        return back()->with('status', 'Se ha reenviado el código a tu correo.');
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

    protected function sendVerificationCode(User $user): void
    {
        $code = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $user->codigo_verificacion = $code;
        $user->codigo_timestamp = now();
        $user->save();

        Mail::to($user->correo_electronico)->send(new TwoFactorCodeMail($user->nombre, $code));
    }
}

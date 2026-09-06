<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TwoFactorLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_two_factor_code_after_login(): void
    {
        Mail::fake();

        User::factory()->create([
            'nombre' => 'Kevin',
            'correo_electronico' => 'kevin@test.com',
            'password' => 'secret123',
            'estado' => 'Activo',
        ]);

        $response = $this->from('/login')->post('/login', [
            'correo_electronico' => 'kevin@test.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('login.verify.form'));
        $this->assertDatabaseHas('usuarios', [
            'correo_electronico' => 'kevin@test.com',
        ]);
        $this->assertNotNull(session('two_factor_user_id'));
    }
}

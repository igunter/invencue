<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        // Don't depend on a real key being present in .env locally or in CI —
        // pin a fake secret and fake the verify call it triggers.
        config(['services.recaptcha.secret_key' => 'test-secret']);

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'action' => 'register',
                'score' => 0.9,
            ]),
        ]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            // Not example.com: it deliberately has a "null MX" record (RFC
            // 7505) declaring it accepts no mail, which the MX validation on
            // this form correctly rejects.
            'email' => 'test@gmail.com',
            'date_of_birth' => now()->subYears(10)->toDateString(),
            'password' => 'password',
            'password_confirmation' => 'password',
            'g-recaptcha-response' => 'fake-token',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}

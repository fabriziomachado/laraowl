<?php

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
});

test('new users can register', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();

    $user = User::where('email', 'test@example.com')->first();
    $response->assertRedirect("/{$user->currentTeam->slug}/dashboard");
});

test('registration is disabled when ALLOW_REGISTRATION is false', function () {
    $_ENV['ALLOW_REGISTRATION'] = 'false';
    $this->refreshApplication();

    $response = $this->get('/register');
    $response->assertStatus(404);

    $responseStore = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    $responseStore->assertStatus(404);

    unset($_ENV['ALLOW_REGISTRATION']);
    $this->refreshApplication();
});

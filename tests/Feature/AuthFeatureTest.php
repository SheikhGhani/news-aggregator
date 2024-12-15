<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration.
    */
    public function testUserRegistration()
    {
        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ];

        $response = $this->postJson('/api/auth/register', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    /**
     * Test user login.
    */
    public function testUserLogin()
    {
        // Create a user
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('Password123'),
        ]);

        $payload = [
            'email' => 'john@example.com',
            'password' => 'Password123',
        ];

        $response = $this->postJson('/api/auth/login', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at',
                    ],
                    'token',
                ],
            ]);
    }

    /**
     * Test user logout.
    */
    public function testUserLogout()
    {
        // Create a user
        $user = User::factory()->create();

        // Act as the user and get the token
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Logged out successfully',
                'data' => null,
            ]);
    }
    /**
     * Test forgot password.
    */
    public function testForgotPassword()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
        ]);

        Password::shouldReceive('sendResetLink')
            ->once()
            ->andReturn(Password::RESET_LINK_SENT);

        $response = $this->postJson('/api/auth/forgot-password', [
            'email' => $user->email,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Password reset link sent successfully',
            ]);
    }

    /**
     * Test forgot password with non-existing email.
    */
    public function testForgotPasswordNonExistentEmail()
    {
        // Send request with an email that doesn't exist
        $response = $this->postJson('/api/auth/forgot-password', [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The selected email is invalid.',
            ]);
    }
}

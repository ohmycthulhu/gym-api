<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Trainer;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Feature test for checking clients
     *
     * @return void
     */
    public function testClients() {
        $this->checkLogin(Client::class, 'clients', 'trainers');
    }

    /**
     * Feature test for checking trainers
     *
     * @return void
    */
    public function testTrainers() {
        $this->checkLogin(Trainer::class, 'trainers', 'clients');
    }

    /**
     * Test base for testing login
     *
     * @param string $model
     * @param string $correctGuard
     * @param string $invalidGuard
     *
     * @return void
     */
    protected function checkLogin(string $model, string $correctGuard, string $invalidGuard)
    {
        $password = Str::random();
        // Create model
        $user = factory($model)->create(['password' => Hash::make($password)]);

        // Try to login with invalid credentials
        $body = ['email' => $user->email, 'password' => $password];
        $response = $this->post(route('api.login', ['type' => $invalidGuard]), $body);
        $response->assertStatus(401);

        // Try to login with valid credentials
        $response = $this->post(route('api.login', ['type' => $correctGuard]), $body);
        $response->assertStatus(200);
        $token = $response->json('access_token');

        // Check token
        $response = $this->get(route('api.me', ['type' => $invalidGuard]),
            [ 'headers' => ["Authorization" => "Bearer $token"]]
        );
        $response->assertStatus(401);

        $response = $this->get(route('api.me', ['type' => $correctGuard]),
            [ 'headers' => ["Authorization" => "Bearer $token"]]
        );
        $response->assertStatus(200);
    }
}

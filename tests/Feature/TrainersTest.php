<?php

namespace Tests\Feature;

use App\Models\Trainer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class TrainersTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUpdate()
    {
        $password = 'password';
        // Create trainer
        $trainer = factory(Trainer::class)
            ->create(['password' => Hash::make($password)]);

        // Login with credentials
        $response = $this->post(route('api.login', ['type' => 'trainers']), ['email' => $trainer->email, 'password' => $password]);
        $response->assertStatus(200);
        $token = $response->json('access_token');

        // Update the data
        $newName = Str::random();
        $startTime = "08:00";
        $endTime = "19:00";
        /* Send invalid data */
        $response = $this->put(route('api.trainer'), ['name' => $newName, 'shift_end_time' => $startTime, 'shift_start_time' => $endTime], [
            'headers' => ['Authorization' => "Bearer $token"]
        ]);
        $response->assertStatus(403);

        /* Send valid data */
        $response = $this->put(route('api.trainer'), ['name' => $newName, 'shift_start_time' => $startTime, 'shift_end_time' => $endTime], [
            'headers' => ['Authorization' => "Bearer $token"]
        ]);
        $response->assertStatus(200);

        // Check if data is the same as updated
        $this->assertEquals($newName, $response->json('user.name'));
        $this->assertEquals($startTime, $response->json('user.shift_start_time'));
        $this->assertEquals($endTime, $response->json('user.shift_end_time'));
    }
}

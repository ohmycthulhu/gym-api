<?php

namespace Tests\Feature;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        /* TODO: Insert the authentication */
    }

    /**
     * Feature test for checking trainers
     *
     * @return void
    */
    public function testTrainers() {
        /* TODO: Implement the method */
    }

    /**
     * Test base for testing login
     *
     * @param Authenticatable $model
     *
     * @return void
     */
    protected function checkLogin(Authenticatable $model)
    {
        // Create model

        // Try to login with invalid credentials

        // Try to login with valid credentials

        // Check if model is the same
    }
}

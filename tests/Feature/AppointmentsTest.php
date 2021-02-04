<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Trainer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppointmentsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A feature test to get trainers information
     *
     * @return void
    */
    public function testTrainersPages() {
        $trainers = factory(Trainer::class, 10)->create();
        $this->checkRoute(route('trainers'));
        foreach ($trainers as $trainer) {
            $this->checkRoute(route('trainers.appointments', ['trainerId' => $trainer->id]));
            $this->checkRoute(route('trainers.schedule', ['trainerId' => $trainer->id]));
        }
    }

    /**
     * A feature test to test clients
     *
     * @return void
    */
    public function testClientsPages() {
        $clients = factory(Client::class, 10)->create();
        foreach ($clients as $client) {
            $this->checkRoute(route('clients.appointments', ['clientId' => $client->id]));
        }
    }

    /**
     * Protected function test route
     *
     * @param string $route
     *
     * @return void
    */
    protected function checkRoute(string $route) {
        $response = $this->get($route);

        $response->assertStatus(200);
    }
}

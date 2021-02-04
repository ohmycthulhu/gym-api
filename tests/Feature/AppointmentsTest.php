<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppointmentsTest extends TestCase
{
    use RefreshDatabase;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        /* TODO: Seed the database */
    }

    /**
     * A feature test to get trainers information
     *
     * @return void
    */
    public function testTrainersExistence() {
        /* TODO: Implement the method */
        // Send request and check whether response is empty or not
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAppointments()
    {
        /* TODO: Implement the method */
        // Send request to check if trainer is free in given time

        // Randomly book the time

        // Check if trainer is still free

        // Check if user can book time that overlaps with current time

        // Cancel the appointment

        // Book again
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Appointments\Appointment;
use App\Models\Appointments\AppointmentData;
use App\Models\Client;
use App\Models\Trainer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    protected $client;
    protected $appointment;

    public function __construct(Trainer $trainer, Client $client, Appointment $appointment, AppointmentData $appointmentData)
    {
        $this->client = $client;
        $this->appointment = $appointment;
    }

    /**
     * Method to get client's appointments
     *
     * @param int $clientId
     *
     * @return JsonResponse
     */
    public function getAppointmentById(int $clientId): JsonResponse {
        $appointments = $this->appointment::query()
            ->byUser($clientId)
            ->with(['data.user'])
            ->get();

        return response()->json(['appointments' => $appointments]);
    }

    /**
     * Method to get my appointments
     *
     * @return JsonResponse
    */
    public function getMyAppointments(): JsonResponse {
        return $this->getAppointmentById(auth()->id());
    }


    /**
     * Method to remove appointment
     *
     * @param int $appointmentId
     *
     * @return JsonResponse
     */
    public function removeAppointment(int $appointmentId): JsonResponse {
        $user = auth()->user();

        $appointments = $user->initializedAppointments()->where('id', $appointmentId)->get();

        foreach($appointments as $appointment) {
            $appointment->data()->delete();
            $appointment->delete();
        }

        return response()->json([
            'status' => sizeof($appointments) ? 'Success' : 'Not found',
            'count' => sizeof($appointments)
        ], sizeof($appointments) ? 200 : 404);
    }
}

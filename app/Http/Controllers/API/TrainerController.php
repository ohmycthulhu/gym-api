<?php

namespace App\Http\Controllers\API;

use App\Helpers\TimeHelper;
use App\Http\Controllers\Controller;
use App\Models\Appointments\Appointment;
use App\Models\Trainer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Type\Time;

class TrainerController extends Controller
{
    protected $trainer;
    protected $appointment;

    public function __construct(Trainer $trainer, Appointment $appointment)
    {
        $this->trainer = $trainer;
        $this->appointment = $appointment;
    }

    /**
     * Method to get list of all trainers
     *
     * @return JsonResponse
     */
    public function all(): JsonResponse{
        $trainers = $this->trainer::all();
        return response()->json(['trainers' => $trainers]);
    }

    /**
     * Method to update information about current trainer
     *
     * @param Request $request
     *
     * @return JsonResponse
    */
    public function update(Request $request): JsonResponse {
        $user = auth()->user();
        $params = $request->only('name', 'shift_start_time', 'shift_end_time');
        $shiftStartTime = $params['shift_start_time'] ?? $user->shift_start_time;
        $shiftEndTime = $params['shift_end_time'] ?? $user->shift_end_time;

        if (!TimeHelper::isTimeValid($shiftStartTime) || !TimeHelper::isTimeValid($shiftEndTime)) {
            return response()->json(['error' => 'Time is malformed'], 403);
        }

        if (TimeHelper::timeDiff($shiftEndTime, $shiftStartTime) <= 0) {
            return response()->json(['error' => 'Time difference is invalid'], 403);
        }

        $user->update($params);

        return response()->json(['user' => $user, 'request' => $request->all()]);
    }


    /**
     * Method to get time information on trainer schedule
     *
     * @param int $trainerId
     *
     * @return JsonResponse
     */
    public function getSchedule(int $trainerId): JsonResponse {
        $trainer = $this->trainer::findOrFail($trainerId);
        $appointments = $trainer->weekAppointments()->get();

        $result = [];
        $date = Carbon::now();
        for ($i = 0; $i < 7; $i++) {
            array_push($result, $this->getScheduleForDate($trainer, $appointments, $date->format('Y-m-d')));
            $date->add('days', 1);
        }

        return response()->json([
            'trainer' => $trainer,
            'schedule' => $result
        ]);
    }

    /**
     * Method to get information about free time
     *
     * @param Trainer $trainer
     * @param Collection $appointments
     * @param string $date
     *
     * @return array
    */
    protected function getScheduleForDate(Trainer $trainer, Collection $appointments, string $date): array {
        $freeTimes = [[$trainer->shift_start_time, $trainer->shift_end_time]];

        $neededAppointments = $appointments->filter(function ($a) use ($date) {
            return $a->date === $date;
        })->sortBy('start_time');

        foreach ($neededAppointments as $appointment) {
            $lastFreeTime = last($freeTimes);
            if ($appointment->start_time <= $lastFreeTime[0]) {
                unset($freeTimes[sizeof($freeTimes) - 1]);
            } else {
                $newLastTime = [$lastFreeTime[0], $appointment->start_time];
                $freeTimes[sizeof($freeTimes) - 1] = $newLastTime;
            }
            if ($appointment->end_time < $lastFreeTime[1]) {
                $newTime = [$appointment->end_time, $lastFreeTime[1]];
                array_push($freeTimes, $newTime);
            }
        }

        return [
            'date' => $date,
            'free_times' => $freeTimes,
            'appointments' => $neededAppointments,
        ];
    }


    /**
     * Method to get appointments of trainer
     *
     * @param int $trainerId
     *
     * @return JsonResponse
     */
    public function getAppointmentsById(int $trainerId): JsonResponse {
        $appointments = $this->appointment::query()
            ->byTrainer($trainerId)
            ->with(['data.user'])
            ->get();

        return response()->json(['appointments' => $appointments]);
    }

    /**
     * Method to book the appointment
     *
     * @param Request $request
     * @param int $trainerId
     *
     * @return JsonResponse
     */
    public function bookAppointment(Request $request, int $trainerId) {
        $trainer = $this->trainer::where('id', $trainerId)->first();
        if (!$trainer) {
            return response()->json(['error' => 'Trainer not exists'], 403);
        }

        // Get inputs
        $from = $request->input('start_time');
        $to = $request->input('end_time');
        $date = $request->input('date');
        $companions = $request->input('companions', []);
        $user = auth()->user();

        // Check inputs
        $carbonDate = Carbon::parse($date);
        if ((!$carbonDate || $carbonDate < Carbon::now()) ||
            !TimeHelper::isTimeValid($from) || !TimeHelper::isTimeValid($to)
            || TimeHelper::timeDiff($to, $from) <= 0
        ) {
            return response()->json([
                'errors' => [
                    'start_time' => !TimeHelper::isTimeValid($from),
                    'end_time' => !TimeHelper::isTimeValid($to),
                    'duration' => TimeHelper::timeDiff($to, $from) <= 0,
                    'date' => (!$carbonDate || $carbonDate < Carbon::now())
                ]
            ], 403);
        }

        // Check if client is available on this date
        if (!$this->checkIfUserAvailable($user->id, $from, $to)) {
            return response()->json(['error' => 'You are not available at this time'], 403);
        }

        // Check if companions are available too
        $unavailableCompanions = array_filter($companions, function ($id) use ($from, $to) {
            return !$this->checkIfUserAvailable($id, $from, $to);
        });
        if ($unavailableCompanions) {
            return response()
                ->json(['error' => 'Some companions are not available', 'data' => $unavailableCompanions], 403);
        }

        // Check if trainer is available
        if (!$this->checkIfTrainerAvailable($trainer, $from, $to)) {
            return response()
                ->json(['error' => 'Trainer is not available at the time'], 403);
        }

        $users = array_merge($companions, [$user->id]);
        // Book an appointment
        $appointment = $user->initializedAppointments()->create([
            'date' => $date,
            'start_time' => $from,
            'end_time' => $to,
            'trainer_id' => $trainer->id,
            'clients_list' => join('', array_map(function ($f) { return "|$f|"; }, $users))
        ]);

        foreach ($users as $user) {
            $appointment->data()->create(['client_id' => $user]);
        }

        // Return response
        return response()->json([
            'status' => 'Success',
            'appointment' => $appointment,
        ]);
    }

    /**
     * Method to check if user is available
     *
     * @param int $userId
     * @param string $from
     * @param string $to
     *
     * @return boolean
    */
    protected function checkIfUserAvailable(int $userId, string $from, string $to): bool {
        return $this->appointment::query()
                ->byUser($userId)
                ->byDates($from, $to)
                ->first() === null;
    }

    /**
     * Method to check availability of trainer
     *
     * @param Trainer $trainer
     * @param string $from
     * @param string $to
     *
     * @return boolean
    */
    protected function checkIfTrainerAvailable(Trainer $trainer, string $from, string $to): bool {
        return $this->appointment::query()
                ->byTrainer($trainer->id)
                ->byDates($from, $to)
                ->first() === null;
    }
}

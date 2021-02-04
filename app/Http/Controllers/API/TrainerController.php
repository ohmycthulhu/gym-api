<?php

namespace App\Http\Controllers\API;

use App\Helpers\TimeHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
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
}

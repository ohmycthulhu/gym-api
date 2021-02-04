<?php

namespace App\Models\Appointments;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentData extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id', 'appointment_id'
    ];

    public function client(): BelongsTo {
        return $this->belongsTo(Client::class, 'user_id', 'id');
    }

    public function appointment(): BelongsTo {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'id');
    }
}

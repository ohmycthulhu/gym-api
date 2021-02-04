<?php

namespace App\Models\Appointments;

use App\Models\Client;
use App\Models\Trainer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'start_time', 'end_time',
        'date',
        'trainer_id', 'user_id', 'users_list'
    ];

    public function data(): HasMany {
        return $this->hasMany(AppointmentData::class, 'appointment_id', 'id');
    }

    public function trainer(): BelongsTo {
        return $this->belongsTo(Trainer::class, 'trainer_id', 'id');
    }

    public function initializedBy(): BelongsTo {
        return $this->belongsTo(Client::class, 'user_id', 'id');
    }
}

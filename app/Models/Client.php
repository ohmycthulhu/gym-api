<?php

namespace App\Models;

use App\Models\Appointments\Appointment;
use App\Models\Appointments\AppointmentData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'email', 'password'
    ];

    protected $hidden = [
        'password'
    ];

    public function appointments(): HasMany {
        return $this->hasMany(AppointmentData::class, 'user_id', 'id');
    }

    public function initializedAppointments(): HasMany {
        return $this->hasMany(Appointment::class, 'user_id', 'id');
    }
}

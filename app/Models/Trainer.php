<?php

namespace App\Models;

use App\Models\Appointments\Appointment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Trainer extends Authenticatable
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'email', 'password',
        'shift_start_time', 'shift_end_time',
    ];

    protected $hidden = [
        'password'
    ];

    public function appointments(): HasMany {
        return $this->hasMany(Appointment::class, 'trainer_id', 'id');
    }
}

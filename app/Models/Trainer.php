<?php

namespace App\Models;

use App\Models\Appointments\Appointment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Trainer extends Authenticatable implements JWTSubject
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

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}

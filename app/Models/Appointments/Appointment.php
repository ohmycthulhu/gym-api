<?php

namespace App\Models\Appointments;

use App\Models\Client;
use App\Models\Trainer;
use Illuminate\Database\Eloquent\Builder;
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
        'trainer_id', 'client_id', 'clients_list'
    ];

    protected $hidden = [
        'clients_list'
    ];

    public function data(): HasMany {
        return $this->hasMany(AppointmentData::class, 'appointment_id', 'id');
    }

    public function trainer(): BelongsTo {
        return $this->belongsTo(Trainer::class, 'trainer_id', 'id');
    }

    public function initializedBy(): BelongsTo {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    /**
     * Scope by user
     *
     * @param Builder $query
     * @param int $clientId
     *
     * @return Builder
    */
    public function scopeByUser(Builder $query, int $clientId): Builder {
        return $query->where('clients_list', 'LIKE', "%|$clientId|%");
    }

    /**
     * Scope by user
     *
     * @param Builder $query
     * @param int $trainerId
     *
     * @return Builder
    */
    public function scopeByTrainer(Builder $query, int $trainerId): Builder {
        return $query->where('trainer_id', $trainerId);
    }


    /**
     * Scope by from and to date
     *
     * @param Builder $query
     * @param string $from
     * @param string $to
     *
     * @return Builder
     */
    public function scopeByDates(Builder $query, string $from, string $to): Builder {
        return $query->where(function ($q) use ($from, $to) {
            $q->where(function ($q) use($from, $to) {
               /* Inner date */
                $q->where('start_time', '<', $from)
                    ->where('end_time', '>', $to);
            })->orWhere(function ($q) use ($from, $to) {
                /* Outside date */
                $q->where('start_time', '>', $from)
                    ->where('end_time', '<', $to);
            })->orWhere(function ($q) use ($from, $to) {
                /* Left shifted */
                $q->where('start_time', '>', $from)
                    ->where('start_time', '<', $to);
            })->orWhere(function ($q) use ($from, $to) {
                /* Right shifted */
                $q->where('end_time', '>', $from)
                    ->where('end_time', '<', $to);
            });
        });
    }
}

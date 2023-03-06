<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Passwords\CanResetPassword;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $table = "customers";

    public $timestamps = false;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'typeTraining',
        'dateInscription',
        'nextPayment',
        'trainer_id',
    ];

    protected $hidden = [
        'password',
        'updated_at',
        'created_at'
    ];

    public function payment(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    /**
     * @property Collection|Training[] $trainings
     */
    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class, 'id_customer', 'id');
    }

    public function imcRecord(): HasMany
    {
        return $this->hasMany(ImcRecord::class);
    }
}

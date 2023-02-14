<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $table = "customers";

    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone',
        'password',
        'registration_date'
    ];

    protected $hidden = [
        'password',
        'updated_at',
        'created_at'
    ];

    public function payment() {
        return $this->hasMany(Payment::class);
    }

    public function trainer() {
        return $this->belongsTo(Trainer::class);
    }

    public function trainings() {
        return $this->hasMany(Training::class, 'id_customer', 'id');
    }
}

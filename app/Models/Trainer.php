<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Passwords\CanResetPassword;

class Trainer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $table = "trainers";

    protected $fillable = [
        'name',
        'surname',
        'email',
        'specialty',
        'password',
    ];

    protected $hidden = [
        'password',
        'updated_at',
        'created_at'
    ];

    public function customer() {
        return $this->hasMany(Customer::class);
    }
}

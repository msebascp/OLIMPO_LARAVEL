<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    public $table = "trainers";
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone',
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

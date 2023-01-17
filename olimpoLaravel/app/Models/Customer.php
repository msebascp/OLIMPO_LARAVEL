<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public $table = "customers";
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
        'updated_at',
        'created_at'
    ];

    public function inscription() {
        return $this->hasOne(Inscription::class);
    }

    public function trainer() {
        return $this->belongsTo(Trainer::class);
    }
}

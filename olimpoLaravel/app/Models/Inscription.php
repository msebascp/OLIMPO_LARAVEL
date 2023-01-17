<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    public $table = "inscriptions";
    use HasFactory;

    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}

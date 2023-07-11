<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shopping extends Model
{
    use HasFactory;

    protected $table = 'shopping';

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function customer() {
        return $this->hasMany(Customer::class);
    }
}

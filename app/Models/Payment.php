<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public $table = 'payments';
    use HasFactory;

    protected $fillable = [
        'payment_type',
        'payment_date',
        'paid'
    ];

    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImcRecord extends Model
{
    public $table = 'imc_records';
    use HasFactory;

    protected $fillable = [
        'weighing_date',
        'weight',
        'height',
        'imc',
        'customer_id'
    ];

    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}

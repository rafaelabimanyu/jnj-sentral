<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FieldOperation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'operation_date',
        'bensin_parkir_fee',
        'entertain_fee',
        'bonus_fee',
        'description',
        'receipt_path',
    ];

    protected $casts = [
        'operation_date' => 'date',
        'bensin_parkir_fee' => 'decimal:2',
        'entertain_fee' => 'decimal:2',
        'bonus_fee' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function technicians()
    {
        return $this->hasMany(FieldOperationTechnician::class, 'field_operation_id');
    }

    public function getTotalWagesAttribute()
    {
        return $this->technicians->sum('wage_amount');
    }

    public function getTotalCostAttribute()
    {
        return $this->total_wages + $this->bensin_parkir_fee + $this->entertain_fee + $this->bonus_fee;
    }
}

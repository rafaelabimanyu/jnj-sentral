<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldOperationTechnician extends Model
{
    protected $fillable = [
        'field_operation_id',
        'technician_name',
        'wage_amount',
    ];

    protected $casts = [
        'wage_amount' => 'decimal:2',
    ];

    public function fieldOperation()
    {
        return $this->belongsTo(FieldOperation::class, 'field_operation_id');
    }
}

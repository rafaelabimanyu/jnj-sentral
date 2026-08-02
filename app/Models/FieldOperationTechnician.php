<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldOperationTechnician extends Model
{
    protected $fillable = [
        'field_operation_id',
        'employee_id',
        'technician_id', // for backward compatibility during transitions
        'wage_amount',
    ];

    protected $casts = [
        'wage_amount' => 'decimal:2',
    ];

    public function fieldOperation()
    {
        return $this->belongsTo(FieldOperation::class, 'field_operation_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Alias relationship for backward compatibility with field operation components.
     */
    public function technician()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}

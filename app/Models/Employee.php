<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $table = 'employees';

    protected $fillable = [
        'name',
        'role',
        'level',
        'status',
    ];

    public function operationDetails()
    {
        return $this->hasMany(FieldOperationTechnician::class, 'employee_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Technician extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'level',
    ];

    public function operationDetails()
    {
        return $this->hasMany(FieldOperationTechnician::class, 'technician_id');
    }
}

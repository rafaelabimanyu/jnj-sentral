<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'income_id',
        'client_name',
        'category',
        'amount',
        'description',
        'status',
        'approved_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function income()
    {
        return $this->belongsTo(Income::class, 'income_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

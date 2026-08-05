<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OverheadExpense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category',
        'title',
        'amount',
        'description',
        'receipt_path',
        'expense_date',
        'is_prorated',
        'proration_days',
        'daily_amount',
        'proration_start_date',
        'proration_end_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
        'is_prorated' => 'boolean',
        'proration_days' => 'integer',
        'daily_amount' => 'decimal:2',
        'proration_start_date' => 'date',
        'proration_end_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

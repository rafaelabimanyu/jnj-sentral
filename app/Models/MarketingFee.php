<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketingFee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'marketer_id',
        'project_value',
        'fee_percentage',
        'fee_amount',
        'status',
        'payment_date',
    ];

    protected $casts = [
        'project_value' => 'decimal:2',
        'fee_percentage' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function marketer()
    {
        return $this->belongsTo(Marketer::class, 'marketer_id');
    }
}

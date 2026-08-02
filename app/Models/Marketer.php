<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marketer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'default_fee_percentage',
    ];

    protected $casts = [
        'default_fee_percentage' => 'decimal:2',
    ];

    public function marketingFees()
    {
        return $this->hasMany(MarketingFee::class);
    }
}

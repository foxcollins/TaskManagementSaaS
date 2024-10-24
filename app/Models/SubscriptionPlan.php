<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
        'currency',
        'is_active',
    ];

    public function getDurationInDaysAttribute()
    {
        return "{$this->duration} days";
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoalLog extends Model
{
    protected $fillable = [
        'goal_id',
        'amount',
        'date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date'
    ];

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }
}

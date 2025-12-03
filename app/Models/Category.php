<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'limit',
        'type'
    ];

    protected $casts = [
        'limit' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function monthlyBudget()
    {
        return $this->hasMany(MonthlyBudget::class);
    }
    
}

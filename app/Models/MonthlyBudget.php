<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'month',
        'year',
        'planned_amount',
        'category_id',
        'notes',
    ];

    // Akses cepat untuk sisa budget
    public function getRemainingAmountAttribute()
    {
        return $this->planned_amount - $this->actual_amount;
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getActualAmountAttribute()
    {
        return Transaction::where('category_id', $this->category_id)
            ->whereMonth('date', date("m", strtotime($this->month)))
            ->whereYear('date', $this->year)
            ->sum('amount');
    }
}

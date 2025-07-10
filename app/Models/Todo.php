<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Todo extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function scopePending($query)
    {
        return $query->where('completed', false);
    }

    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }

    public function scopeNeedsReminder($query)
    {
        return $query->where('reminder_sent', false)
                    ->where('completed', false)
                    ->where('due_date', '<=', Carbon::now()->addMinutes(10))
                    ->where('due_date', '>', Carbon::now());
    }

    public function getFormattedDueDateAttribute()
    {
        return $this->due_date ? $this->due_date : null;
    }
}

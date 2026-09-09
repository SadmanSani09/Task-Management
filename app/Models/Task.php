<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'priority', 'deadline',
        'status', 'category_id', 'created_by'
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class)->withPivot('completed_at')->withTimestamps();
    }

    // Scope to filter by status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Check if overdue
    public function isOverdue()
    {
        return $this->deadline->isPast() && $this->status !== 'completed';
    }

    // Update status automatically before saving
    protected static function booted()
    {
        static::saving(function ($task) {
            if ($task->deadline->isPast() && $task->status !== 'completed') {
                $task->status = 'overdue';
            }
        });
    }
}
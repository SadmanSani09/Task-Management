<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title', 'description', 'priority', 'deadline',
        'status', 'category_id', 'created_by',
    ];

    protected $casts = ['deadline' => 'date'];

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

    protected static function booted()
    {
        static::saving(function ($task) {
            if ($task->deadline && $task->deadline->isPast() && $task->status !== 'completed') {
                $task->status = 'overdue';
            }
        });
    }
}
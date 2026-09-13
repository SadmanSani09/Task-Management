<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'body',
        'is_read_by_admin',
    ];

    protected $casts = [
        'is_read_by_admin' => 'boolean',
    ];

    public function task():BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
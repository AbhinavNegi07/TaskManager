<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'image', 'priority', 'is_completed', 'due_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
{
    static::creating(function ($task) {
        if (empty($task->slug)) {
            $task->slug = Str::slug($task->title) . '-' . uniqid();
        }
    });
}
}

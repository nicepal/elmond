<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'title',
        'course_id',
        'owner_id',
        'owner_type',
        'sort_order',
        'status',
        'created_at',
        'updated_at'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function owner()
    {
        return $this->morphTo();
    }

    // Add this missing relationship
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'module_id');
    }

    // Scope for active modules
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
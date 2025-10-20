<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\Notifiable;

class Organization extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $fillable = [
        'company_name',
        'contact_person_name',
        'email',
        'password',
        'designation',
        'country_code',
        'mobile',
        'address',
        'city',
        'state',
        'zip',
        'status'
    ];
    
    protected $casts = [
        'address' => 'array',
        'password' => 'hashed',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'organization_users')
                    ->withPivot('employee_id', 'department', 'position', 'joined_at', 'status')
                    ->withTimestamps();
    }
    
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'organization_courses')
                    ->withPivot('assigned_price', 'assigned_at', 'expires_at', 'max_enrollments', 'status')
                    ->withTimestamps();
    }
    
    public function activeUsers()
    {
        return $this->users()->wherePivot('status', 1);
    }
    
    public function activeCourses()
    {
        return $this->courses()->wherePivot('status', 1);
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    
    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }
    
    // Accessors
    public function fullAddress(): Attribute
    {
        return new Attribute(
            get: fn() => $this->address . ', ' . $this->city . ', ' . $this->state . ' ' . $this->zip
        );
    }
    
    public function statusBadge(): Attribute
    {
        return new Attribute(
            get: fn() => $this->status == 1 
                ? '<span class="badge badge--success">Active</span>' 
                : '<span class="badge badge--warning">Inactive</span>'
        );
    }
}

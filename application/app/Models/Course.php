<?php

namespace App\Models;

use App\Models\Enroll;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    // Add to $fillable array
    protected $fillable = [
        'name', 'category_id', 'owner_id', 'owner_type', 'status', 'admin_status',
        'price', 'discount', 'image', 'course_outline', 'learn_description', 
        'curriculum', 'description', 'preview_video', 'instructor_image', 'instructor_details',
        'seo_title', 'seo_description', 'launch_type', 'launch_date', 'selected_categories',
        'duration', 'assignments_count', 'access_duration', // Removed learners_count and course_level
        'course_faqs', 'certificate_description', 'certificate_image'
    ];

    // Add to $casts array
    protected $casts = [
        'course_outline' => 'object',
        'launch_date' => 'date',
        'registration_deadline' => 'date',
        'is_featured' => 'boolean',
        'selected_categories' => 'array',
        'course_faqs' => 'array',
    ];

    static function instructorCourseCategories()
    {
        return self::where('owner_type', 2);
    }

    static function adminCourseCategories()
    {
        return self::where('owner_id', auth('admin')->id())->where('owner_type', 1);
    }

    function scopeAdminOwner($query){
       return $query->where('owner_id',auth('admin')->id())->where('owner_type',1);
    }
    // New scopes for launch types
    public function scopeNewLaunch($query)
    {
        return $query->where('launch_type', 'new_launch');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('launch_type', 'upcoming');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeRegular($query)
    {
        return $query->where('launch_type', 'regular');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Helper methods
    public function isNewLaunch()
    {
        return $this->launch_type === 'new_launch';
    }

    public function isUpcoming()
    {
        return $this->launch_type === 'upcoming';
    }

    public function isLaunched()
    {
        return $this->launch_date && $this->launch_date->isPast();
    }

    public function getEffectivePrice()
    {
        // Return early bird price if available and registration is still open
        if ($this->early_bird_price && 
            $this->registration_deadline && 
            $this->registration_deadline->isFuture()) {
           return $this->early_bird_price;
        }
        return $this->price;
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'course_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function enrolls()
    {
        return $this->hasMany(Enroll::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function quizCertificate()
    {
        return $this->hasMany(QuizCertificate::class);
    }
    
    // Add this new relationship
    public function modules()
    {
        return $this->hasMany(Module::class, 'course_id')->orderBy('sort_order', 'asc');
    }
    
    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_courses')
                    ->withPivot('assigned_price', 'assigned_at', 'expires_at', 'max_enrollments', 'status')
                    ->withTimestamps();
    }
    
    public function checkedPurchase()
    {
        return  $this->enrolls->where('user_id', auth()->id())->first();
    }
}

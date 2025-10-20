<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory;

    // Add fillable array
    protected $fillable = [
        'title',
        'course_id', 
        'module_id',
        'owner_id',
        'owner_type',
        'level',
        'preview_video',
        'value',
        'video_url',
        'upload_video',
        'description'
    ];

    protected $casts = [
        'tags' => 'object',
        'zoom_data' => 'object'
    ];

    function scopeInstructorOwner($query){
       return $query->where('owner_id',auth('instructor')->id())->where('owner_type',2);
    }

    function scopeAdminOwner($query){
       return $query->where('owner_id',auth('admin')->id())->where('owner_type',1);
    }

    function course_category() {
        return $this->belongsTo(Course::class,'course_id');
    }

    // Add module relationship
    function module() {
        return $this->belongsTo(Module::class,'module_id');
    }

    function scopeStep($scope)
    {
        return $this->where('step', $scope)->get();
    }
    
    function lessonDocuments()
    {
        return $this->hasMany(LessonDocument::class);
    }

    function stepOne(){
        return $this->where('step',1);
    }

    function stepTwo($id){
        return $this->where('step',2);
    }

    function stepThree($id){
        return $this->where('step',3);
    }
}

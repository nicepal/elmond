<?php

namespace App\Http\Controllers\Admin;

use Closure;
use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;

class CourseController extends Controller
{
    function index(Request $request)
    {
        $pageTitle = 'My Course';
        $courses = Course::adminCourseCategories();
        $categories = Category::where('status', 1)->get();
        if ($request->search) {
            $courses = $courses->where('name', 'like', "%$request->search%")->paginate(getPaginate());
        } else {
            $courses = $courses->orderBy('id','desc')->paginate(getPaginate());
        }
        return view('admin.courses.index', compact('pageTitle', 'courses', 'categories'));
    }

    function instructorCourses(Request $request)
    {
        
        $pageTitle = 'Instructor Course';
        $courses =  Course::instructorCourseCategories()->orderBy('id','desc');
        if ($request->search) {
            $courses = $courses->with('quizzes')->where('name', 'like', "%$request->search%")->paginate(getPaginate());
        } else {
            $courses = $courses->with('quizzes')->paginate(getPaginate());
        }
        return view('admin.courses.instructor', compact('pageTitle', 'courses'));
    }

    function create (){
        $pageTitle = 'Create Course';
        $categories = Category::where('status', 1)->get();
        return view('admin.courses.create', compact('pageTitle','categories'));
    }

    function store(CourseRequest $request)
    {
        $pageTitle = 'Create My Course Category';
        
        // Validate categories array
        if (empty($request->categories) || !is_array($request->categories)) {
            $notify[] = ['error', 'At least one category must be selected'];
            return back()->withNotify($notify);
        }
        
        // Get first category as primary
        $primaryCategoryId = $request->categories[0];
        $category = Category::findOrFail($primaryCategoryId);
        if (!$category) {
            $notify[] = ['error', 'Primary category is not valid'];
            return back()->withNotify($notify);
        }
    
        $course = new Course();
    
        // -------------------------Check only this user already same name create or not--------------------
        $request->validate([
            'name' => [
                function (string $attribute, mixed $value, Closure $fail) use ($course) {
                    $existing_course_names = $course->adminCourseCategories()->pluck('name')->map(function ($name) {
                        return strtolower($name);
                    });
    
                    if ($existing_course_names->contains(strtolower($value))) {
                        $fail('Your course category name already exists.');
                    }
                },
            ],
            'launch_date' => 'nullable|date|after_or_equal:today',
            'registration_deadline' => 'nullable|date|after_or_equal:today',
            'early_bird_price' => 'nullable|numeric|min:0',
        ]);
    
        // Basic course information
        $course->name = $request->name;
        $course->category_id = $primaryCategoryId;
        $course->selected_categories = $request->categories;
        $course->owner_id = auth('admin')->id();
        $course->owner_type = 1;
        $course->status = $request->status;
        $course->admin_status = 1;
        $course->price = $request->price;
        $course->discount = $request->discount ?? null;
        
        // Launch information
        $course->launch_type = $request->launch_type ?? 'regular';
        $course->launch_date = $request->launch_date;
        
        // Course content (remove duplicates)
        $course->learn_description = $request->learn_description;
        $course->course_outline = $request->course_outline;
        $course->curriculum = $request->curriculum;
        $course->description = $request->description; // Now nullable
        // $course->preview_video = $request->preview_video;
        
        // About the Course fields - ADD THESE
        $course->duration = $request->duration;
        $course->assignments_count = $request->assignments_count;
        $course->access_duration = $request->access_duration;
        
         if ($request->hasFile('preview_video')) {
                $file = $request->file('preview_video');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                // $path = $file->storeAs('uploads/videos', $filename, 'public');
                 // Move file directly to public/uploads/videos
                $file->move(public_path('../assets/uploads/videos'), $filename);
                $course->preview_video = 'assets/uploads/videos/' . $filename;
                // Save path into DB
                // $course->preview_video = $path;
        }
            
        // Process course FAQs
        if ($request->has('course_faqs') && is_array($request->course_faqs)) {
            $faqs = [];
            foreach ($request->course_faqs as $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $faqs[] = [
                        'question' => $faq['question'],
                        'answer' => $faq['answer']
                    ];
                }
            }
            $course->course_faqs = $faqs;
        }
        
        // Instructor information
        if ($request->hasFile('instructor_image')) {
            try {
                $course->instructor_image = fileUploader($request->instructor_image, getFilePath('instructor_image'), getFileSize('instructor_image'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Instructor image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }
        $course->instructor_details = $request->instructor_details;
        
        // SEO information
        $course->seo_title = $request->seo_title;
        $course->seo_description = $request->seo_description;
        
        // Certificate information
        $course->certificate_description = $request->certificate_description;
        if ($request->hasFile('certificate_image')) {
            try {
                $course->certificate_image = fileUploader($request->certificate_image, getFilePath('certificate'), getFileSize('certificate'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Certificate image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }
        
        // Course main image
        if ($request->hasFile('image')) {
            try {
                $course->image = fileUploader($request->image, getFilePath('course_image'), getFileSize('course_image'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        
        $course->save();
        $notify[] = ['success', 'Course created successfully'];
        return back()->withNotify($notify);
    }

    // Add new methods for different course types
    function newLaunches()
    {
        $pageTitle = 'New Launch Courses';
        $courses = Course::with('category')
            ->newLaunch()
            ->adminOwner()
            ->orderBy('launch_date', 'desc')
            ->paginate(getPaginate());
        
        return view('admin.courses.index', compact('pageTitle', 'courses'));
    }

    function upcomingCourses()
    {
        $pageTitle = 'Upcoming Courses';
        $courses = Course::with('category')
            ->upcoming()
            ->adminOwner()
            ->orderBy('launch_date', 'asc')
            ->paginate(getPaginate());
        
        return view('admin.courses.index', compact('pageTitle', 'courses'));
    }

    function featuredCourses()
    {
        $pageTitle = 'Featured Courses';
        $courses = Course::with('category')
            ->featured()
            ->adminOwner()
            ->orderBy('created_at', 'desc')
            ->paginate(getPaginate());
        
        return view('admin.courses.index', compact('pageTitle', 'courses'));
    }

    function edit($id){
        $pageTitle = 'Edit Course';
        $categories = Category::where('status', 1)->get();
        $course= Course::findOrFail($id);
        return view('admin.courses.edit', compact('pageTitle','categories','course'));
    }

    function update(CourseRequest $request, $id)
    {
        $pageTitle = 'Update My Course Category';
        
        // Validate categories array
        if (empty($request->categories) || !is_array($request->categories)) {
            $notify[] = ['error', 'At least one category must be selected'];
            return back()->withNotify($notify);
        }
        
        // Get first category as primary
        $primaryCategoryId = $request->categories[0];
        
        $course = Course::where('id', $id)->first();
        $old_image = $course->image;
        
        // Get first category as primary
        $primaryCategoryId = $request->categories[0];
        
        $course->name = $request->name;
        $course->category_id = $primaryCategoryId; // First selected category
        $course->selected_categories = $request->categories; // All selected categories
        $course->owner_id = auth('admin')->id();
        $course->owner_type = 1;
        $course->status = $request->status;
        $course->admin_status = 1;
        $course->price = $request->price;
        $course->discount = $request->discount ?? null;
        
        // Only launch type and launch date
        $course->launch_type = $request->launch_type ?? 'regular';
        $course->launch_date = $request->launch_date;
        
        $course->learn_description = $request->learn_description;
        $course->course_outline = $request->course_outline;
        $course->curriculum = $request->curriculum;
        // In the store method, add after description:
        $course->description = $request->description;
        $course->preview_video = $request->preview_video;
        
        // In the update method, add after description:
        $course->description = $request->description;
        $course->preview_video = $request->preview_video;
        // In store method, add after existing assignments:
        $course->preview_video = $request->preview_video;
        
        // About the Course fields
        $course->duration = $request->duration;
        $course->assignments_count = $request->assignments_count;
        $course->access_duration = $request->access_duration;
        // Remove these two lines:
        // $course->learners_count = $request->learners_count ?? 0;
        // $course->course_level = $request->course_level;
         if ($request->hasFile('preview_video')) {
                $file = $request->file('preview_video');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                // $path = $file->storeAs('uploads/videos', $filename, 'public');
                // Save path into DB
                        // Create unique filename

                // Move file directly to public/uploads/videos
                 // Absolute path to assets/videos folder (outside Laravel "application")
                $destinationPath = base_path('../assets/uploads/videos');  

                // Make sure the folder exists
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0775, true);
                }

                // Move uploaded file
                $file->move($destinationPath, $filename);
                // $file->move(public_path('assets/uploads/videos'), $filename);
                $course->preview_video = 'assets/uploads/videos/' . $filename;
        }

        // Course FAQs
        $faqs = [];
        if ($request->has('faqs')) {
            foreach ($request->faqs as $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $faqs[] = [
                        'question' => $faq['question'],
                        'answer' => $faq['answer']
                    ];
                }
            }
        }
        $course->course_faqs = $faqs;
        
        // Certificate fields
        $course->certificate_description = $request->certificate_description;
        if ($request->hasFile('certificate_image')) {
            try {
                $course->certificate_image = fileUploader($request->certificate_image, getFilePath('certificate'), getFileSize('certificate'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Certificate image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }
        
        if ($request->hasFile('instructor_image')) {
            try {
                $old_instructor_image = $course->instructor_image;
                $course->instructor_image = fileUploader($request->instructor_image, getFilePath('instructor_image'), getFileSize('instructor_image'), $old_instructor_image);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Instructor image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }
        
        $course->instructor_details = $request->instructor_details;
        $course->seo_title = $request->seo_title;
        $course->seo_description = $request->seo_description;
        if ($request->hasFile('image')) {
            try {
                $course->image = fileUploader($request->image, getFilePath('course_image'), getFileSize('course_image'), $old_image);
            } catch (Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        $course->save();
        $notify[] = ['success', 'Course updated successfully'];
        return back()->withNotify($notify);
    }


    function adminStatusApproved(Request $request, $id)
    {
        $request->validate([
            'admin_status' => 'required|in:0,1'
        ]);

        $course = Course::findOrFail($id);
        $course->admin_status = $request->admin_status;
        $course->save();
        $notify[] = ['success', 'Course Admin Approved Successfully'];
        return redirect()->back()->withNotify($notify);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Closure;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\LessonDocument;
use App\Rules\FileTypeValidate;
use App\Services\LessonService;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Instructor\ZoomController;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;

class LessonController extends Controller
{

    function courses(Request $request, $id)
    {
        $pageTitle = 'Lessons';
        $lessons = Lesson::with('course_category')->adminOwner()->where('course_id', $id)->orderBy('id', 'desc');
        if ($request->search) {
            $lessons = $lessons->where('title', 'like', "%$request->search%")->paginate(getPaginate());
        } else {
            $lessons = $lessons->paginate(getPaginate());
        }

        return view('admin.lessons.index', compact('pageTitle', 'lessons','id'));
    }


    function instructorLessons(Request $request, $id)
    {
        $pageTitle = 'Instructor Courses';
        $lesson = Lesson::with('course_category')->where('owner_type', 2)->where('course_id', $id);
        if ($request->search) {
            $courses = $lesson->where('title', 'like', "%$request->search%")->paginate(getPaginate());
        } else {
            $lesson = $lesson->paginate(getPaginate());
        }
        return view('admin.lessons.instructor_index', compact('pageTitle', 'lesson'));
    }

    function create($lessonId = null)
    {
        $pageTitle = 'Create Lesson';
        $courses = Course::adminCourseCategories()->get();
        $modules = collect(); // Empty collection initially
        $upload_video = null;

        if (session()->get('videoUpload')) {
            $oldFileName = session()->get('videoUpload');
            fileManager()->removeFile(getFilePath('videoUpload') . '/videoUpload/' . $oldFileName);
            session()->forget('videoUpload');
        }

        return view('admin.lessons.create', compact('pageTitle', 'courses', 'modules', 'lessonId'));
    }

    function edit($id)
    {
        $pageTitle = 'Edit Lesson';
        $categories = Category::where('status', 1)->get();
        $course = Course::adminCourseCategories()->get();
        $lesson = Lesson::adminOwner()->where('id', $id)->first();

        if (!$lesson->first()) {
            $notify[] = ['error', 'Your id is not valid'];
            return back()->withNotify($notify);
        }

        // Get modules for the selected course
        $modules = Module::where('course_id', $lesson->course_id)->where('status', 1)->get();

        $upload_video = null;
        if (session()->get('videoUpload')) {
            $oldFileName = session()->get('videoUpload');
            fileManager()->removeFile(getFilePath('videoUpload') . '/videoUpload/' . $oldFileName);
            session()->forget('videoUpload');
        }
        if ($lesson->upload_video) {
            $upload_video = asset(getFilePath('videoUpload') . '/videoUpload/' . $lesson->upload_video);
        }

        return view('admin.lessons.edit', compact('pageTitle', 'categories', 'lesson', 'course', 'modules', 'upload_video'));
    }

    // Add this new method to get modules via AJAX
    function getModulesByCourse($courseId) 
    { 
        // Debug: Log the incoming courseId
        \Log::info('getModulesByCourse called with courseId: ' . $courseId);
        
        // Debug: Check if courseId is null or empty
        if (empty($courseId)) {
            \Log::error('courseId is empty or null');
            return response()->json(['error' => 'Course ID is required'], 400);
        }
        
        $modules = Module::where('course_id', $courseId)->where('status', 1)->get(); 
        
        // Debug: Log the query and results
        \Log::info('Modules query result count: ' . $modules->count());
        \Log::info('Modules data: ' . $modules->toJson());
        
        return response()->json($modules); 
    }

    function store(Request $request)
    {
        $lesson = new Lesson();
        $data = $request->all();
        $validationResult = $this->lessonService->createValidateLesson($data, 'adminOwner');

        if ($validationResult !== true) {
            $flattenArray = collect($validationResult)->flatten();
            $notify[] = ['error', $flattenArray[0]];
            return back()->withNotify($notify);
        }
        $sessionVideo = session()->get('videoUpload');


        if (!$sessionVideo && $request->preview_video == 1) {
            $validationResult = $this->lessonService->validateUploadVideo($data, $lesson);
            if ($validationResult !== true) {
                $flattenArray = collect($validationResult)->flatten();
                $notify[] = ['error', $flattenArray[0]];
                return back()->withNotify($notify);
            }
        }

        if ($request->start_time && $request->start_time < now()) {
            $notify[] = ['error', 'your current date is less then meeting date'];
            return back()->withNotify($notify);
        }

        if ($request->preview_video == 3 && $request->start_time && $request->start_time > now()) {
            $zoomResponse = $this->lessonService->validateZoomDataAndCreateMeeting($request->all(), auth('admin')->user());

            if (!$zoomResponse['status']) {
                // Handle the error case
                return back()->withNotify(['error', 'Meeting is not created']);
            }
        }

        DB::beginTransaction();

        try {
            $lessonDocPaths = []; // Array to store file paths
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $document) {
                    // Upload each document and store its path
                    $lessonDocPaths[] = fileUploader($document, getFilePath('lesson_documents'));
                }
            }
            $purifier = new \HTMLPurifier();
            $lesson->title = $request->title;
            $lesson->owner_id = auth('admin')->id();
            $lesson->owner_type = 1;
            $lesson->course_id = $request->course_id;
            $lesson->module_id = $request->module_id; // Add this line
            $lesson->preview_video = $request->preview_video;
            $lesson->video_url = $request->video_url;
            $lesson->level = $request->level;
            $lesson->value = $request->value;
            $lesson->upload_video = $sessionVideo ?? null;
            $lesson->description = $purifier->purify($request->description);
            $lesson->status = 1;
            $lesson->zoom_data = $zoomResponse['data'] ?? null;
            session()->forget('videoUpload');
            $lesson->save();

            // Save lesson document
            if (isset($lessonDocPaths) && !empty($lessonDocPaths)) {
                foreach ($lessonDocPaths as $path) {
                    $lessonDoc = new LessonDocument();
                    $lessonDoc->lesson_id = $lesson->id;
                    $lessonDoc->file = $path;
                    $lessonDoc->save();
                }
            }

            DB::commit(); // Commit the transaction if all operations succeed
            $notify[] = ['success', 'Lesson created successfully'];
            return redirect()->back()->withNotify($notify);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction on any error
            $notify[] = ['error', 'Something went wrong. Please try again.'];
            return back()->withNotify($notify)->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function videoUpload(Request $request)
    {
        $data = $this->lessonService->commonVideoUpload($request);
        return $data;
    }

    public function editVideoUpload(Request $request)
    {
        $data = $this->lessonService->commonVideoUpload($request);
        return response()->json($data);
    }

    public function videoUploadDelete(Request $request)
    {
        $data = $this->lessonService->commonVideoUploadDelete($request);
        return $data;
    }

    function Update(Request $request, $id)
    {
        $lessonObj = new Lesson();
        $lesson = Lesson::adminOwner()->findOrFail($id);
        $data = $request->all();
        $uploadVideo = $lesson->upload_video;
        // ------------------------validation------------------------
        $validationResult = $this->lessonService->updateValidateLesson($data, 'adminOwner', $lessonObj, $lesson);
        if ($validationResult !== true) {
            $flattenArray = collect($validationResult)->flatten();
            $notify[] = ['error', $flattenArray[0]];
            return back()->withNotify($notify);
        }
        // ------------------------request document validation------------------------
        $lessonDocCount = $lesson->lessonDocuments->count();
        $uploadedDocs = $request->documents ?? [];
        if ($lessonDocCount < 1 && empty($uploadedDocs) && $request->preview_video == '4') {
            return redirect()->back()
                ->withErrors(['documents' => 'At least one document is required when the upload type is document.'])
                ->withInput();
        }

        $sessionVideo = session()->get('videoUpload');
   
        $path = asset(getFilePath('videoUpload') . '/videoUpload/' . $uploadVideo);
        $ch = curl_init($path);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        if (!$sessionVideo && !$uploadVideo && $httpCode != 200) {
            $request->validate([
                'upload_video' => "mimes:mp4|required_if:preview_video,1",
            ]);
        } else {
            $request->validate([
                'upload_video' => "mimes:mp4",
            ]);
        }
        if ($request->preview_video && $sessionVideo) {
            if ($httpCode == 200) {
                
                fileManager()->removeFile(getFilePath('videoUpload') . '/videoUpload/' . $uploadVideo);
            }
        }

        DB::beginTransaction();
        try {
            $lessonDocPaths = []; // Array to store file paths
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $document) {
                    // Upload each document and store its path
                    $lessonDocPaths[] = fileUploader($document, getFilePath('lesson_documents'));
                }
            }

            $purifier = new \HTMLPurifier();
            $lesson->title = $request->title;
            $lesson->course_id = $request->course_id;
            $lesson->module_id = $request->module_id; // Add this line
            $lesson->owner_id = auth('admin')->id();
            $lesson->owner_type = 1;
            $lesson->level = $request->level;
            $lesson->preview_video = $request->preview_video;
            $lesson->value = $request->value;
            $lesson->video_url = $request->preview_video == 2 ? $request->video_url : null;
            $lesson->upload_video = $sessionVideo ?? $uploadVideo;
            $lesson->description = $purifier->purify($request->description);
            session()->forget('videoUpload');
            $lesson->save();
            // Save lesson document
            if (isset($lessonDocPaths) && !empty($lessonDocPaths)) {
                foreach ($lessonDocPaths as $path) {
                    $lessonDoc = new LessonDocument();
                    $lessonDoc->lesson_id = $lesson->id;
                    $lessonDoc->file = $path;
                    $lessonDoc->save();
                }
            }

            DB::commit(); // Commit the transaction if all operations succeed
            $notify[] = ['success', 'Lesson update successfully'];
            return redirect()->back()->withNotify($notify);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction on any error
            $notify[] = ['error', 'Something went wrong. Please try again.'];
            return back()->withNotify($notify)->withErrors(['error' => $e->getMessage()]);
        }
    }

   
    public function editVideoUploadDelete(Request $request)
    {
        $data = $this->lessonService->commonEditVideoUploadDelete($request, 'admin');
        return response()->json($data);
    }

    function lessonDelete($id)
    {
        $data = $this->lessonService->commonLessonDelete($id, 'admin');
        return redirect()->back()->withNotify($data);
    }

    function documentDelete($id)
    {
        $data = $this->lessonService->commonDocumentDelete($id);
        return response()->json($data);
    }
}

<?php

namespace App\Http\Controllers\Instructor;

use DB;
use Closure;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LessonDocument;
use App\Rules\FileTypeValidate;
use App\Services\LessonService;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;

class LessonController extends Controller
{

    function lessons(Request $request, $id)
    {
        $pageTitle = 'Course Lessons';
        $lessons = Lesson::instructorOwner()->where('course_id', $id)->orderBy('id', 'desc');
        if ($request->search) {
            $lessons = $lessons->where('title', 'like', "%$request->search%")->paginate(getPaginate());
        } else {
            $lessons = $lessons->paginate(getPaginate());
        }
        return view($this->activeTemplate . 'instructor.lessons.index', compact('pageTitle', 'lessons'));
    }

    function create($lessonId = null)
    {
        $pageTitle = 'Create Lesson';
        $courses = Course::instructorCourseCategories()->get();

        $upload_video = null;

        if (session()->get('videoUpload')) {
            $oldFileName = session()->get('videoUpload');
            fileManager()->removeFile(getFilePath('videoUpload') . '/videoUpload/' . $oldFileName);
            session()->forget('videoUpload');
        }
        return view($this->activeTemplate . 'instructor.lessons.create', compact('pageTitle', 'courses', 'lessonId'));
    }

    function store(Request $request)
    {
        $lesson = new Lesson();
        $data = $request->all();

        $validationResult = $this->lessonService->createValidateLesson($data, 'instructorOwner');
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
            $zoomResponse = $this->lessonService->validateZoomDataAndCreateMeeting($request->all(), auth('instructor')->user());

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
            // Save lesson data
            $purifier = new \HTMLPurifier();
            $lesson = new Lesson(); // Assuming $lesson is being created anew
            $lesson->title = $request->title;
            $lesson->owner_id = auth('instructor')->id();
            $lesson->owner_type = 2;
            $lesson->course_id = $request->course_id;
            $lesson->preview_video = $request->preview_video;
            $lesson->video_url = $request->video_url;
            $lesson->level = $request->level;
            $lesson->value = $request->value;
            $lesson->upload_video = $sessionVideo ?? null;
            $lesson->description = $purifier->purify($request->description);
            $lesson->status = $request->status;
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

    public function videoUploadDelete(Request $request)
    {
        $data = $this->lessonService->commonVideoUploadDelete($request);
        return $data;
    }

    function edit($id)
    {
        $pageTitle = 'Edit Lesson';
        $categories = Category::where('status', 1)->get();
        $courses = Course::instructorCourseCategories()->get();
        $lesson = Lesson::with('lessonDocuments')->instructorOwner()->where('id', $id)->first();

        if (!$lesson->first()) {
            $notify[] = ['error', 'Your id is not valid'];
            return back()->withNotify($notify);
        }

        $upload_video = null;
        if (session()->get('videoUpload')) {
            $oldFileName = session()->get('videoUpload');
            fileManager()->removeFile(getFilePath('videoUpload') . '/videoUpload/' . $oldFileName);
            session()->forget('videoUpload');
        }
        if ($lesson->upload_video) {
            $upload_video = asset(getFilePath('videoUpload') . '/videoUpload/' . $lesson->upload_video);
        }

        return view($this->activeTemplate . 'instructor.lessons.edit', compact('pageTitle', 'categories', 'lesson', 'courses', 'upload_video'));
    }

    public function editVideoUpload(Request $request)
    {

        $data = $this->lessonService->commonVideoUpload($request);
        return response()->json($data);
    }

    public function editVideoUploadDelete(Request $request)
    {
        $data = $this->lessonService->commonEditVideoUploadDelete($request, 'instructor');
        return response()->json($data);
    }


    function Update(Request $request, $id)
    {
        $lessonObj = new Lesson();
        $lesson = Lesson::with('lessonDocuments')->instructorOwner()->findOrFail($id);
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
            $lesson->owner_id = auth('instructor')->id();
            $lesson->owner_type = 2;
            $lesson->level = $request->level;
            $lesson->preview_video = $request->preview_video;
            $lesson->value = $request->value;
            $lesson->video_url = $request->preview_video == 2 ? $request->video_url : null;
            $lesson->upload_video = $sessionVideo ?? $uploadVideo;
            $lesson->description = $purifier->purify($request->description);
            $lesson->status = $request->status;
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



    function lessonDelete($id)
    {
        $data = $this->lessonService->commonLessonDelete($id, 'instructor');
        return redirect()->back()->withNotify($data);
    }

    function documentDelete($id)
    {
        $data = $this->lessonService->commonDocumentDelete($id);
        return response()->json($data);
    }
}

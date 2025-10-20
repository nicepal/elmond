<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Lesson;
use App\Models\LessonDocument;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Instructor\ZoomController;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;

class LessonService
{
    function createValidateLesson($data, $owner)
    {
        $lesson = new Lesson();
        $rules = [
            'course_id' => 'required|numeric',
            'title' => [
                'required',
                function (string $attribute, mixed $value, \Closure $fail) use ($lesson, $owner) {
                    $existing_lesson_names = $lesson?->{$owner}()?->pluck('title')->map(function ($name) {
                        return strtolower($name);
                    });
                    if ($existing_lesson_names && $existing_lesson_names->contains(strtolower($value))) {
                        $fail('Your Lesson title name already exists.');
                    }
                },
            ],
            'level' => ['required', 'numeric', Rule::in(['1', '2', '3'])],
            'value' => ['required', Rule::in(['0', '1'])],
            'preview_video' => ['required', Rule::in(['1', '2', '3'])],
            'video_url' => 'required_if:preview_video,2',
            'description' => 'required|string',
            'agenda' => 'required_if:preview_video,3',
            'class_topic' => 'required_if:preview_video,3',
            'type' => ['required_if:preview_video,3', Rule::in(['1', '2'])],
            'approximate_time' => 'required_if:preview_video,3',
            'email' => 'required_if:preview_video,3',
            'password' => 'required_if:preview_video,3',
            'start_time' => 'required_if:preview_video,3',
            'approval_type' => ['required_if:preview_video,3', Rule::in(['0', '1'])],
            'documents' => ['nullable', 'array'], // Allow multiple files
            'documents.*' => 'mimes:jpg,jpeg,png,ppt,txt,doc,docx,pdf|max:5120',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return $validator->errors(); // Return the validation errors
        }
        return true; // Validation passed
    }

    function UpdateValidateLesson($data, $owner, $lessonObj, $lesson)
    {
    
        $rules = [
            'course_id' => "required|numeric",
            'level' => "required|numeric|" . Rule::in(['1', '2', '3']),
            'value' => "required|" . Rule::in(['0', '1']),
            'title' => [
                'required',
                function (string $attribute, mixed $value, \Closure $fail) use ($lessonObj, $owner, $lesson) {
                    $existing_lesson_names = $lessonObj?->{$owner}()?->whereNot('id', $lesson->id)->pluck('title')->map(function ($name) {
                        return strtolower($name);
                    });
                    if ($existing_lesson_names->contains(strtolower($value))) {
                        $fail('Your course title name already exists.');
                    }
                },
            ],
            'preview_video' => "required|" . Rule::in(['1', '2', '3','4']),
            'video_url' => "required_if:preview_video,2",
            'description' => "required|string",
            'documents' => ['nullable', 'array'],
            'documents.*' => 'mimes:jpg,jpeg,png,ppt,doc,docx,pdf,zip,xls,xlsx,csv,pptx,bmp,webp|max:5120',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {

            return $validator->errors(); // Return the validation errors
        }
     

        $sessionVideo = session()->get('videoUpload');
        $uploadVideo = $lesson->upload_video;
        $path = asset(getFilePath('videoUpload') . '/videoUpload/' . $uploadVideo);
        $ch = curl_init($path);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!$sessionVideo && !$uploadVideo && $httpCode != 200) {
            $rules = [
                'upload_video' => "mimes:mp4|required_if:preview_video,1",
            ];
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                return $validator->errors(); // Return the validation errors
            }
        } else {
            $rules = [
                'upload_video' => "mimes:mp4",
            ];
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                return $validator->errors(); // Return the validation errors
            }
        }

        if ($data['preview_video'] && $sessionVideo) {
            if ($httpCode == 200) {
                fileManager()->removeFile(getFilePath('videoUpload') . '/videoUpload/' . $uploadVideo);
            }
        }

        return true; // Validation passed
    }

    function validateUploadVideo($request)
    {
        // Define the rules for validation
        $rules = [
            'upload_video' => [
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('preview_video') == 1 && !$request->hasFile('upload_video')) {
                        $fail('Upload video is required when preview video is set.');
                    }
                },
                $request->preview_video == 1 ? 'required_if:preview_video,1' : '', // Make sure it's required if preview_video is 1
                $request->preview_video == 1 ? 'mimes:mp4,mov' : '' // Ensure mp4 format when preview_video is 1
            ]
        ];

        // Perform validation
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails and return errors
        if ($validator->fails()) {
            return $validator->errors();
        }

        return true; // Return true if validation passes
    }

    function validateZoomDataAndCreateMeeting($request, $owner)
    {
        $request = (object)$request;
        $start_date = Carbon::parse($request->start_time);

        // Build Zoom data array
        $zoom_array = [
            'agenda' => $request->agenda ?? null,
            'class_topic' => $request->class_topic ?? null,
            'type' => (int) ($request->type ?? null),
            'approximate_time' => (int) ($request->approximate_time ?? null),
            'password' => $request->password ?? null,
            'start_time' => $start_date->format('Y-m-d\TH:i:s'),
            'approval_type' => (int) ($request->approval_type ?? null),
            'email' => $request->email ?? null,
        ];

        // Check if any value is null
        $collectionZoomData = collect($zoom_array);
        if ($collectionZoomData->contains(function ($value) {
            return is_null($value);
        })) {
            // Some data are missing
            return ['status' => false, 'message' => 'Some data are missing'];
        }

        // Proceed with Zoom setup
        $user = $owner;
        if (!$user->zoom_account_id && !$user->zoom_client_id && !$user->zoom_secret_id) {
            return ['status' => false, 'message' => 'At first setup Zoom credentials'];
        }

        // Attempt to store the Zoom meeting
        $zoom = new ZoomController();
        $zoom_data = $zoom->storeMeeting($collectionZoomData);
        if (!$zoom_data['status']) {
            return ['status' => false, 'message' => $zoom_data['message']];
        }

        return ['status' => true, 'data' => $zoom_data];
    }

    function commonVideoUpload($data)
    {
        $receiver = new FileReceiver('file', $data, HandlerFactory::classFromRequest($data));
        if (!$receiver->isUploaded()) {
            // file not uploaded
        }

        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            $fileName = str_replace('.' . $extension, '', $file->getClientOriginalName()); //file name without extenstion
            $fileName .= '_' . md5(time()) . '.' . $extension; // a unique file name
            $fileName = str_replace(' ', '_', $fileName);

            $uploadPath = getFilePath('videoUpload');
            $path = $uploadPath . '/videoUpload/' . $fileName;
            $file->move($uploadPath . '/videoUpload', $fileName);
            Storage::disk('local')->delete('chunks/' . $fileName);

            if (session()->get('videoUpload')) {
                $oldFileName = session()->get('videoUpload');
                fileManager()->removeFile(getFilePath('videoUpload') . '/videoUpload/' . $oldFileName);
            }

            session()->put('videoUpload', $fileName);

            return [
                'path' => asset($path),
                'filename' => $fileName
            ];
        }

        // otherwise return percentage information
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
    }

    function commonVideoUploadDelete($requestData)
    {
        $data = '';
        $fileName = session()->get('videoUpload') ?? $requestData->fileName;
        $path = asset(getFilePath('videoUpload') . '/videoUpload/' . $fileName);
        $ch = curl_init($path);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200) {
            fileManager()->removeFile(getFilePath('videoUpload') . '/videoUpload/' . $fileName);
            session()->forget('videoUpload');
            $data = [
                'status' => "success",
                'message' => "Your upload video is removed",
            ];
        } else {
            $data = [
                'status' => "error",
                'message' => "Your upload video url is not valid",
            ];
        }

        return response()->json($data);
    }

    function commonLessonDelete($id, $type = 'admin')
    {

        $lessonQuery = $type === 'admin' ? Lesson::adminOwner() : Lesson::instructorOwner();
        $lesson = $lessonQuery->with('lessonDocuments')->find($id);

        if (!$lesson) {
            $notify[] = ['error', 'Your id is not valid'];
            return redirect()->back()->withNotify($notify);
        }
        $fileName = $lesson->upload_video;
        $path = asset(getFilePath('videoUpload') . '/videoUpload/' . $fileName);
        $ch = curl_init($path);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200) {
            fileManager()->removeFile(getFilePath('videoUpload') . '/videoUpload/' . $fileName);
        }

        if ($lesson->lessonDocuments) {
            foreach ($lesson->lessonDocuments as $item) {

                if (File::exists(getFilePath('lesson_documents') . '/' . $item->file)) {
                    fileManager()->removeFile(getFilePath('lesson_documents') . '/' . $item->file);
                }
                $item->delete(); // Ensure the database entry is also deleted
            }
        }

        // Delete the lesson
        $isDeleted = $lesson->delete();

        // Notify user based on deletion success
        if ($isDeleted) {
            $notify[] = ['success', 'Your lesson deleted successfully'];
        } else {
            $notify[] = ['error', "Your lesson couldn't be deleted successfully"];
        }

        return $notify;
    }

    function commonDocumentDelete($id)
    {
       
        $lessonDoc = LessonDocument::findOrFail($id);
        try {
            if (File::exists(getFilePath('lesson_documents') . '/' . $lessonDoc->file)) {
                fileManager()->removeFile(getFilePath('lesson_documents') . '/' . $lessonDoc->file);
            }
            $lessonDoc->delete(); // Ensure the database entry is also deleted
            $data = [
                'status' => "success",
                'message' => "Your upload document is removed",
            ];
        } catch (\Exception $e) {
            $data = [
                'status' => "error",
                'message' => "Your upload video is not removed",
            ];
        }

        return $data;
    }


    function commonEditVideoUploadDelete($requestData, $type)
    {
        $data = '';
        $fileName = session()->get('videoUpload') ?? $requestData->fileName;
        $path = asset(getFilePath('videoUpload') . '/videoUpload/' . $fileName);
        $ch = curl_init($path);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!session()->get('videoUpload')) {
            $lessonQuery = $type === 'admin' ? Lesson::adminOwner() : Lesson::instructorOwner();
            $Lesson = $lessonQuery->where('id', $requestData->id)->first();
            $Lesson->upload_video = null;
            $Lesson->save();
        }

        if ($httpCode == 200) {
            fileManager()->removeFile(getFilePath('videoUpload') . '/videoUpload/' . $fileName);
            session()->forget('videoUpload');
            $data = [
                'status' => "success",
                'message' => "Your upload video is removed",
            ];
        } else {
            $data = [
                'status' => "error",
                'message' => "Your upload video url is not valid",
            ];
        }
        return $data;
    }
}

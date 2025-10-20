<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Carbon\Carbon;
use App\Models\Page;
use App\Models\Course;
use App\Models\Enroll;
use App\Models\Lesson;
use App\Models\Review;
use App\Models\Category;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use Jubaer\Zoom\Facades\Zoom;
use App\Models\CourseCategory;
use App\Models\LessonDocument;
use App\Models\SupportMessage;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cookie;

class SiteController extends Controller
{
    public function index()
    {
        $reference = @$_GET['reference'];
        if ($reference) {
            session()->put('reference', $reference);
        }
        $pageTitle = 'Home';
        $sections = Page::where('tempname', $this->activeTemplate)->where('slug', '/')->first();

        $categories = Category::with('courses')->where('status', 1)->orderBy('id', 'desc')->get();
        $courses = Course::with('lessons', 'category', 'enrolls')
            ->where('admin_status', 1)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->take(16)
            ->get();
        return view($this->activeTemplate . 'home', compact('pageTitle', 'sections', 'categories', 'courses'));
    }

    public function pages($slug)
    {
        $page = Page::where('tempname', $this->activeTemplate)->where('slug', $slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        // dd($pageTitle,$sections,$this->activeTemplate);
        return view($this->activeTemplate . 'pages', compact('pageTitle', 'sections'));
    }

    public function about()
    {
        $pageTitle = "About";
        $sections = Page::where('tempname', $this->activeTemplate)->where('slug', 'about')->firstOrFail();
        return view($this->activeTemplate . 'about', compact('pageTitle', 'sections'));
    }

    public function categories()
    {
        $pageTitle = "Categories";
        $sections = Page::where('tempname', $this->activeTemplate)->where('slug', 'categories')->firstOrFail();

        return view($this->activeTemplate . 'categories.index', compact('pageTitle', 'sections'));
    }

    public function contact()
    {
        $pageTitle = "Contact Us";
        return view($this->activeTemplate . 'contact', compact('pageTitle'));
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:subscribers',
        ]);
        $subscribe = new Subscriber();
        $subscribe->email = $request->email;
        $subscribe->save();
        $notify[] = ['success', 'You have successfully subscribed to the Newsletter'];
        return back()->withNotify($notify);
    }


    public function contactSubmit(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $request->session()->regenerateToken();

        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = 2;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = 0;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = 'A new support ticket has opened ';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        return to_route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function policyPages($slug, $id)
    {
        $policy = Frontend::where('id', $id)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle = $policy->data_values->title;
        return view($this->activeTemplate . 'policy', compact('policy', 'pageTitle'));
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return back();
    }

    public function blogPost()
    {
        $pageTitle = 'All Blogs';
        $blogSection = Frontend::where('data_keys', 'blog.content')->first();
        $blogElementSection = Frontend::where('data_keys', 'blog.element')->orderBy('id', 'desc')->paginate(getPaginate(6));
        $sections = Page::where('tempname', $this->activeTemplate)->where('slug', 'blog')->firstOrFail();
        return view($this->activeTemplate . 'blog.blog-post', compact('pageTitle', 'blogSection', 'blogElementSection', 'sections'));
    }


    public function blogDetails($slug, $id)
    {
        $blog = Frontend::findOrFail($id);
        $blogElementSection = Frontend::where('data_keys', 'blog.element')->orderBy('id', 'desc')->take(4)->get();
        $pageTitle = 'Blog Details';
        return view($this->activeTemplate . 'blog.blog-details', compact('pageTitle', 'blog', 'blogElementSection'));
    }

    public function blogSearch(Request $request)
    {
        $blogs = Frontend::where('data_keys', 'blog.element')->where('data_values->title', 'like', "%$request->searchTerm%")->get();
        $data = [
            'status' => "success",
            'blogs' => $blogs,
        ];
        return response()->json($data);
    }

    public function course(Request $request, $id = null)
    {
        $pageTitle = 'Course';
        $categories = Category::with('courses')->where('status', 1)->orderBy('id', 'desc')->get();
        $courses = Course::with('lessons', 'category', 'enrolls', 'reviews')
            ->where('admin_status', 1)
            ->where('status', 1)
            ->orderBy('id', 'desc');
        
        // Filter by launch type if provided
        if ($request->has('launch_type') && in_array($request->launch_type, ['new_launch', 'upcoming', 'regular'])) {
            $courses = $courses->where('launch_type', $request->launch_type);
            
            // Update page title based on launch type
            switch($request->launch_type) {
                case 'new_launch':
                    $pageTitle = 'New Launch Courses';
                    break;
                case 'upcoming':
                    $pageTitle = 'Upcoming Courses';
                    break;
                case 'regular':
                    $pageTitle = 'Regular Courses';
                    break;
            }
        }
        if ($id) {
            $courses = $courses->where('category_id', $id)->paginate(getPaginate());
        } else {
            $courses = $courses->paginate(getPaginate());
        }
        
        return view($this->activeTemplate . 'course.index', compact('pageTitle', 'courses', 'categories'));
    }

    public function courseSearch(Request $request)
    {
        $pageTitle = 'Course';
        $categories = Category::with('courses')->where('status', 1)->orderBy('id', 'desc')->get();
        $courses = Course::with('lessons', 'category', 'enrolls', 'reviews')
            ->where('admin_status', 1)
            ->where('status', 1)
            ->orderBy('id', 'desc');
        if ($request->name) {
            $courses = $courses->where('name', 'LIKE', "%$request->name%");
        }

        if ($request->value) {
            $courses = $courses->whereHas('lessons', function ($q) use ($request) {
                $q->where('value', $request->value);
            });
        }

        if ($request->review) {
            $courses = $courses->whereHas('reviews', function ($q) use ($request) {
                $q->where('rating', $request->review);
            });
        }

        if ($request->category) {
            $courses = $courses->where('category_id', $request->category);
        }

        $courses = $courses->paginate(getPaginate());
        return view($this->activeTemplate . 'course.index', compact('pageTitle', 'courses', 'categories'));
    }

    public function courseDetails($slug, $id)
    {
        $course = Course::with('category', 'enrolls', 'reviews', 'lessons', 'quizzes')->where('id', $id)->where('admin_status', 1)->where('status', 1)->orderBy('id', 'desc')->first();
        $pageTitle = 'Course';
    
        $ad = Ad::orderBy('id', 'desc')->first();
        $reviews = Review::with('user')->where('course_id', $id)->paginate(getPaginate());
    
        // Create custom SEO data for course
        $seo = null;
        if ($course) {
            // Get default SEO settings from Frontend model
            $defaultSeo = Frontend::where('data_keys', 'seo.data')->first();
            $defaultSeoData = $defaultSeo ? $defaultSeo->data_values : null;
            
            // Create custom SEO object with course data
            $seo = (object) [
                'description' => !empty($course->seo_description) ? $course->seo_description : ($defaultSeoData ? $defaultSeoData->description : ''),
                'social_title' => !empty($course->seo_title) ? $course->seo_title : $course->name,
                'social_description' => !empty($course->seo_description) ? $course->seo_description : ($defaultSeoData ? $defaultSeoData->social_description : ''),
                'keywords' => $defaultSeoData ? $defaultSeoData->keywords : [],
                'image' => $defaultSeoData ? $defaultSeoData->image : '',
                'course_title' => !empty($course->seo_title) ? $course->seo_title : $course->name
            ];
            
            // Update page title if course has SEO title
            if (!empty($course->seo_title)) {
                $pageTitle = $course->seo_title;
            }
        }
    
        return view($this->activeTemplate . 'course.details', compact('pageTitle', 'course', 'ad', 'reviews', 'seo'));
    }


    public function coursePreview(Request $request)
    {
        // Fetch lesson and enrollment details
        $lesson = Lesson::with('course_category', 'lessonDocuments')->where('course_id', $request->course_id)->where('id', $request->id)->first();
        $enroll = Enroll::where('course_id', $request->course_id)->where('user_id', auth()->id())->where('status', 1)->first();


        // Check if lesson exists and is active
        if (!$lesson || $lesson->status == 0) {
            return response()->json([
                'status' => "error",
                'code' => 0,
                'message' => "Course Lesson is not active right now",
            ]);
        }

        // Free lesson case
        if ($lesson->value == 0) {
            return response()->json([
                'status' => "success",
                'code' => 1,
                'data' => $lesson,
                'image' => asset(getFilePath('course_image') . '/' . $lesson->course_category->image),
            ]);
        }

        // Unauthorized user case
        if (!auth()->check()) {
            return response()->json([
                'status' => "error",
                'code' => 0,
                'message' => "Please Login Your Account",
            ]);
        }

        // Premium lesson case
        if ($enroll) {
            $message = "lesson uploaded video";
            if ($lesson->preview_video == 3) {
                $message = $lesson->upload_video ? "lesson Live uploaded video" : "meeting credentials";
            }

            return response()->json([
                'status' => "success",
                'code' => 1,
                'message' => $message,
                'data' => $lesson,
                'image' => asset(getFilePath('course_image') . '/' . $lesson->course_category->image),
            ]);
        }

        // Premium lesson access denied
        return response()->json([
            'status' => "error",
            'code' => 0,
            'message' => "You aren't able to access the Premium lesson",
        ]);
    }

    public function lessonDocumentDownload($id)
    {
        $lessonDoc = LessonDocument::findOrFail($id);
        $filePath = getFilePath('lesson_documents') . '/' . $lessonDoc->file;

        try {
            if (File::exists($filePath)) {
                // Serve the file as a download
                return response()->download($filePath, $lessonDoc->file, [
                    'Content-Type' => File::mimeType($filePath),
                ]);
            } else {
                // File does not exist
                return response()->json([
                    'status' => "error",
                    'message' => "The requested file does not exist.",
                ], 404);
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'status' => "error",
                'message' => "An error occurred while trying to download the file.",
            ], 500);
        }
    }


    public function categoryCourse(Request $request)
    {
        $courses = Course::with('lessons', 'category', 'enrolls')

            ->where('admin_status', 1)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->take(12);
        if ($request->id == 0) {
            $courses = $courses->get();
        } else {
            $courses = $courses->where('category_id', $request->id)->get();
        }


        $view = view('presets.default.components.instructor.category_course', compact('courses'))->render();
        return response()->json([
            'status' => 'success',
            'html' => $view
        ]);
    }

    public function cookieAccept()
    {
        $general = gs();
        Cookie::queue('gdpr_cookie', $general->site_name, 43200);
        return back();
    }

    public function cookiePolicy()
    {
        $pageTitle = 'Cookie Policy';
        $cookie = Frontend::where('data_keys', 'cookie.data')->first();
        return view($this->activeTemplate . 'cookie', compact('pageTitle', 'cookie'));
    }

    public function placeholderImage($size = null)
    {
        $imgWidth = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font') . DIRECTORY_SEPARATOR . 'RobotoMono-Regular.ttf';
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 255, 255, 255);
        $bgFill    = imagecolorallocate($image, 28, 35, 47);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }
}

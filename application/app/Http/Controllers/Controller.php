<?php

namespace App\Http\Controllers;

use App\Services\LessonService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $activeTemplate;
    public $lessonService;

    public function __construct()
    {
        $this->activeTemplate = activeTemplate();

        $className = get_called_class();
        $this->lessonService = new LessonService();
       
    }
}

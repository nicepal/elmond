<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ModuleController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Modules';
        $modules = Module::with('course')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.modules.index', compact('pageTitle', 'modules'));
    }

    public function create()
    {
        $pageTitle = 'Create Module';
        $courses = Course::where('status', 1)->get();
        return view('admin.modules.create', compact('pageTitle', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1'
        ]);

        Module::create([
            'title' => $request->title,
            'course_id' => $request->course_id,
            'owner_id' => auth()->guard('admin')->id(),
            'owner_type' => 1, // 1 for admin, 2 for instructor
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status
        ]);

        $notify[] = ['success', 'Module created successfully'];
        return redirect()->route('admin.modules.index')->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Module';
        $module = Module::findOrFail($id);
        $courses = Course::where('status', 1)->get();
        return view('admin.modules.edit', compact('pageTitle', 'module', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $module = Module::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1'
        ]);

        $module->update([
            'title' => $request->title,
            'course_id' => $request->course_id,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status
        ]);

        $notify[] = ['success', 'Module updated successfully'];
        return redirect()->route('admin.modules.index')->withNotify($notify);
    }

    public function destroy($id)
    {
        $module = Module::findOrFail($id);
        $module->delete();

        $notify[] = ['success', 'Module deleted successfully'];
        return back()->withNotify($notify);
    }
}
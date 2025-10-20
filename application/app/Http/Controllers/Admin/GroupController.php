<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function groups()
    {
        // Placeholder list page
        return response('Admin Groups placeholder', 200);
    }

    public function store(Request $request)
    {
        // Placeholder create
        return back()->with('success', 'Group stored (placeholder)');
    }

    public function update(Request $request, $group)
    {
        // Placeholder update
        return back()->with('success', 'Group updated (placeholder)');
    }
}
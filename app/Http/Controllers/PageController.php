<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;

class PageController extends Controller
{
    public function index()
    {
        //In a production app I would likely use soft-deletes for the Tasks object, then render Tasks::where('is_active', 1), though that seemed unnecessary for this simple of an app.

        $tasks = Task::orderBy('position')->get();
        
        return view('home')->with(['tasks' => $tasks]);
    }
}

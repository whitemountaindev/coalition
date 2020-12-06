<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;
use App\Models\Project;

class PageController extends Controller
{
    public function index()
    {
        //In a production app I would likely use soft-deletes for the Tasks object, then render Tasks::where('is_active', 1), though that seemed unnecessary for this simple of an app.

        $tasks = Task::orderBy('position')->with('project')->get();
        $projects = Project::all();

        return view('home')->with(['tasks' => $tasks, 'projects' => $projects]);
    }

    public function createTask(Request $request)
    {
        $task = new Task;
        $existingTaskPriority = Task::where('position', '=', $request->priority)->first();

        $task->name = $request->name;
        $task->project_id = $request->project;
        if($existingTaskPriority != null){
            $task->save();
            $task->moveBefore($existingTaskPriority);
        }else{
            $task->position = $request->priority;
            $task->save();
        }
    }

    public function editTask(Request $request)
    {
        //I ended up leaving priority out of the edit modal. With the sortable trait on the model/drag and drop on the table, it opened up a lot of confusion, and I think that leaving priority editing to drag and drop is the simplest solution that works well.

        $task = Task::find($request->id);
        $task->name = $request->name;
        $task->project_id = $request->project;

        $task->save();
    }

    public function deleteTask($id)
    {
        $task = Task::find($id);
        $task->delete();
        return redirect()->back();

    }
}

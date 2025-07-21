<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Symfony\Contracts\Service\Attribute\Required;

class TaskController extends Controller
{
    public function index(){
        $tasks = Task::where('completed',false)->orderBy('priority','desc')->orderBy('due_date')->get();
        return view('tasks.index',compact('tasks'));
    }
    public function create(){

        return view('tasks.create');
    }
    public function edit(Task $task) {

        return view('tasks.edit', compact('task'));
    }
    
    public function store(Request $request){
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'priority' => 'required|max:255',
            'due_date' => 'nullable|max:255',
        ]);

        Task::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'priority' => $request->input('priority'),
            'due_date' => $request->input('due_date'),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task Created Successfully');
    }
    public function update(Request $request, Task $task){
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'priority' => 'required|max:255',
            'due_date' => 'nullable|date',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }
    public function destroy(Task $task){
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
    public function complete(Task $task){
        $task->completed = true;
        $task->completed_at = now(); // optional
        $task->save();
        return redirect()->route('tasks.index')->with('success', 'Task completed successfully.');

    }

}

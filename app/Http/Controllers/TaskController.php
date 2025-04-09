<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TaskController extends Controller
{
    // public function index(Request $request)
    // {
    //     $query = Task::query()->where('user_id', Auth::id());

    //     if ($request->filled('completed')) {
    //         $query->where('is_completed', $request->completed);
    //     }

    //     if ($request->filled('priority')) {
    //         $query->where('priority', $request->priority);
    //     }

    //     if ($request->filled('due_date')) {
    //         $query->whereDate('due_date', $request->due_date);
    //     }

    //     $tasks = $query->latest()->get();

    //     return view('tasks.index', compact('tasks'));
    // }

    public function index(Request $request)
    {
        $userId = Auth::id();
    
        // Get selected date or default to today
        $selectedDate = $request->input('date', today()->toDateString());
    
        $query = Task::query()->where('user_id', $userId);
    
        // If overdue filter is set, override all others
        if ($request->filled('overdue') && $request->overdue == 1) {
            $query->where('is_completed', 0)
                  ->whereDate('due_date', '<', today());
                 

        } else {
            // Filter for a specific date (today by default)
            $query->whereDate('due_date', $selectedDate);
    
            if ($request->filled('completed')) {
                $query->where('is_completed', $request->completed);
            }
    
            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }
        }

        // dd($query->pluck('due_date'));
    
        $tasks = $query->latest()->get();
    
        // ✅ Date-based counts for the selected date
        $allCount = Task::where('user_id', $userId)->whereDate('due_date', $selectedDate)->count();
        $pendingCount = Task::where('user_id', $userId)->whereDate('due_date', $selectedDate)->where('is_completed', 0)->count();
        $completedCount = Task::where('user_id', $userId)->whereDate('due_date', $selectedDate)->where('is_completed', 1)->count();
    
        // ✅ Overdue is independent (based on today's date)
        $overdueCount = Task::where('user_id', $userId)
                            ->where('is_completed', 0)
                            ->whereDate('due_date', '<', today())
                            ->count();
    
        return view('tasks.index', compact('tasks', 'allCount', 'pendingCount', 'completedCount', 'overdueCount', 'selectedDate'));
    }
    


    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'priority' => 'required|in:low,medium,high',
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('tasks', 'public') : null;

        Task::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'priority' => $request->priority,
            // 'due_date' => $request->due_date,
            'due_date' => \Carbon\Carbon::parse($request->due_date)->toDateString(), // stores as 'YYYY-MM-DD'

        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'priority' => 'required|in:low,medium,high',
        ]);

        if ($request->hasFile('image')) {
            if ($task->image) {
                Storage::disk('public')->delete($task->image);
            }
            $task->image = $request->file('image')->store('tasks', 'public');
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'image' => $task->image,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        if ($task->image) {
            Storage::disk('public')->delete($task->image);
        }

        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function complete(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $task->update(['is_completed' => true]);
        return redirect()->back()->with('success', 'Task marked as completed.');
    }

    public function show(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        return view('tasks.show', compact('task'));
    }

    public function toggleStatus(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $task->is_completed = !$task->is_completed;
        $task->save();

        return redirect()->back()->with('success', 'Task status updated!');
    }
}

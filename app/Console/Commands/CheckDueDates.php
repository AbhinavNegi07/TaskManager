<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Carbon\Carbon;

class CheckDueDates extends Command
{
    protected $signature = 'tasks:check-due-dates';
    protected $description = 'List tasks with their due date and how they compare to today';

    public function handle()
    {
        $today = Carbon::today();
        $this->info("Today is: " . $today->toDateString() . " (" . config('app.timezone') . ")");

        $tasks = Task::orderBy('due_date')->get(['id', 'title', 'due_date', 'is_completed']);

        if ($tasks->isEmpty()) {
            $this->warn("No tasks found.");
            return;
        }

        $this->line("\nTask Due Date Comparison:");
        $this->table(
            ['ID', 'Title', 'Due Date', 'Status', 'Overdue?'],
            $tasks->map(function ($task) use ($today) {
                return [
                    $task->id,
                    $task->title,
                    $task->due_date,
                    $task->is_completed ? '✅ Completed' : '🕒 Pending',
                    (!$task->is_completed && Carbon::parse($task->due_date)->lt($today)) ? '⚠️ Yes' : 'No',
                ];
            })
        );
    }
}

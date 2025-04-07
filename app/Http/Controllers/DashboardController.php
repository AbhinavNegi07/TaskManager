<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;


class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tasks = $user->tasks()->where('is_completed', true)->get();
        // dd($tasks);

        $stats = [
            'lifetime' => $tasks->count(),
            'year' => $tasks->where('updated_at', '>=', now()->startOfYear())->count(),
            'month' => $tasks->where('updated_at', '>=', now()->startOfMonth())->count(),
            'week' => $tasks->where('updated_at', '>=', now()->startOfWeek())->count(),
        ];

        $priorityBreakdown = [
            'low' => $tasks->where('priority', 'low')->count(),
            'medium' => $tasks->where('priority', 'medium')->count(),
            'high' => $tasks->where('priority', 'high')->count(),
        ];

        // dd($priorityBreakdown);

        // Monthly completion
        // $monthlyStats = $tasks->groupBy(function ($task) {
        //     return Carbon::parse($task->updated_at)->format('m'); // numeric month: "01", "02", etc.
        // })->sortKeys()->mapWithKeys(function ($group, $monthNumber) {
        //     $monthName = Carbon::create()->month((int)$monthNumber)->format('F');
        //     return [$monthName => $group->count()];
        // });

        // Step 1: Get all months in the last 12 months
        $months = collect(CarbonPeriod::create(now()->subMonths(11)->startOfMonth(), '1 month', now()))
            ->mapWithKeys(function ($date) {
                return [$date->format('F') => 0];
            });

        // Step 2: Group existing tasks by month
        $completedMonthly = $tasks->groupBy(function ($task) {
            return Carbon::parse($task->updated_at)->format('F');
        })->map(fn($group) => $group->count());

        // Step 3: Merge to ensure all months are present
        $monthlyStats = $months->merge($completedMonthly);

        // dd($monthlyStats);


        return view('dashboard.index', compact('stats', 'priorityBreakdown', 'monthlyStats'));
    }
}

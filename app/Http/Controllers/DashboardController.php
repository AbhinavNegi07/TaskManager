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

        $stats = [
            'lifetime' => $tasks->count(),
            'Current Year' => $tasks->where('updated_at', '>=', now()->startOfYear())->count(),
            'Current Month' => $tasks->where('updated_at', '>=', now()->startOfMonth())->count(),
            'Current Week' => $tasks->where('updated_at', '>=', now()->startOfWeek())->count(),
        ];

        $priorityBreakdown = [
            'low' => $tasks->where('priority', 'low')->count(),
            'medium' => $tasks->where('priority', 'medium')->count(),
            'high' => $tasks->where('priority', 'high')->count(),
        ];

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

        return view('dashboard.index', compact('stats', 'priorityBreakdown', 'monthlyStats'));
    }
}

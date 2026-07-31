<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Category;
use App\Models\Challenge;
use App\Models\ChallengeSubmission;
use App\Models\Resource;
use App\Models\Roadmap;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'users' => User::count(),
                'resources' => Resource::count(),
                'roadmaps' => Roadmap::count(),
                'challenges' => Challenge::count(),
                'categories' => Category::count(),
            ],
            'pendingSubmissions' => ChallengeSubmission::with(['user', 'challenge'])
                ->where('status', 'pending')->latest()->take(5)->get(),
            'openAssignments' => Assignment::with('user')
                ->whereIn('status', ['assigned', 'submitted'])->latest()->take(5)->get(),
        ]);
    }
}

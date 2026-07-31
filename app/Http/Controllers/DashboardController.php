<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\LessonProgress;
use App\Models\Progress;
use App\Models\QuizAttempt;
use App\Models\Roadmap;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Roadmaps the user has any progress on.
        $startedRoadmapIds = Progress::where('user_id', $user->id)
            ->join('roadmap_steps', 'roadmap_steps.id', '=', 'progress.roadmap_step_id')
            ->distinct()
            ->pluck('roadmap_steps.roadmap_id');

        $roadmaps = Roadmap::with('category')->whereIn('id', $startedRoadmapIds)->get()
            ->map(fn ($r) => [
                'roadmap' => $r,
                'completion' => $r->completionFor($user),
            ]);

        $attempts = QuizAttempt::with('quiz.roadmap')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $assignments = Assignment::with('admin')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $submissions = $user->challengeSubmissions()->with('challenge')->latest()->get();

        // Resume: the most recently studied lesson that isn't finished yet.
        $continue = LessonProgress::with('step.roadmap')
            ->where('user_id', $user->id)
            ->whereNull('completed_at')
            ->latest('updated_at')
            ->first();

        return view('dashboard', [
            'roadmaps' => $roadmaps,
            'attempts' => $attempts,
            'assignments' => $assignments,
            'submissions' => $submissions,
            'continue' => $continue,
            'stats' => [
                'stepsCompleted' => Progress::where('user_id', $user->id)->where('status', 'completed')->count(),
                'quizzesPassed' => $attempts->where('passed', true)->unique('quiz_id')->count(),
                'reviews' => $user->reviews()->count(),
                'projects' => $user->projects()->count(),
            ],
        ]);
    }
}

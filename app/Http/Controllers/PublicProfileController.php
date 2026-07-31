<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\View\View;

class PublicProfileController extends Controller
{
    public function show(User $user): View
    {
        $user->load(['projects' => fn ($q) => $q->latest()]);

        $passedQuizzes = QuizAttempt::with('quiz.roadmap')
            ->where('user_id', $user->id)
            ->where('passed', true)
            ->get()
            ->unique('quiz_id');

        return view('profile.show', [
            'user' => $user,
            'passedQuizzes' => $passedQuizzes,
            'reviewsCount' => $user->reviews()->count(),
        ]);
    }
}

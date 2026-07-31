<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use App\Models\Roadmap;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function show(Roadmap $roadmap): View|RedirectResponse
    {
        $roadmap->load('quiz.questions.options');

        if (! $roadmap->quiz) {
            return redirect()->route('roadmaps.show', $roadmap)
                ->with('error', 'This roadmap does not have a test yet.');
        }

        return view('quiz.show', [
            'roadmap' => $roadmap,
            'quiz' => $roadmap->quiz,
            'lastAttempt' => $roadmap->quiz->bestAttemptFor(auth()->user()),
        ]);
    }

    public function submit(Request $request, Roadmap $roadmap): View|RedirectResponse
    {
        $roadmap->load('quiz.questions.options');
        $quiz = $roadmap->quiz;

        if (! $quiz) {
            return redirect()->route('roadmaps.show', $roadmap);
        }

        $answers = $request->input('answers', []); // [question_id => option_id]

        $total = $quiz->questions->count();
        $correct = 0;
        $review = [];

        foreach ($quiz->questions as $question) {
            $chosen = $answers[$question->id] ?? null;
            $correctOption = $question->options->firstWhere('is_correct', true);
            $isCorrect = $chosen && (int) $chosen === $correctOption?->id;

            if ($isCorrect) {
                $correct++;
            }

            $review[] = [
                'question' => $question,
                'chosen' => $chosen ? (int) $chosen : null,
                'correct_option_id' => $correctOption?->id,
                'is_correct' => $isCorrect,
            ];
        }

        $score = $total > 0 ? (int) round(($correct / $total) * 100) : 0;
        $passed = $score >= $quiz->passing_score;

        $attempt = QuizAttempt::create([
            'user_id' => $request->user()->id,
            'quiz_id' => $quiz->id,
            'score' => $score,
            'correct_count' => $correct,
            'total_count' => $total,
            'passed' => $passed,
            'completed_at' => now(),
        ]);

        return view('quiz.result', [
            'roadmap' => $roadmap,
            'quiz' => $quiz,
            'attempt' => $attempt,
            'review' => $review,
        ]);
    }
}

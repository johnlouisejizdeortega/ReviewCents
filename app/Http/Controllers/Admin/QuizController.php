<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use App\Models\Roadmap;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function edit(Roadmap $roadmap): View
    {
        $quiz = $roadmap->quiz()->with('questions.options')->first();

        return view('admin.quizzes.edit', [
            'roadmap' => $roadmap,
            'quiz' => $quiz,
        ]);
    }

    public function update(Request $request, Roadmap $roadmap): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'passing_score' => ['required', 'integer', 'min:0', 'max:100'],
            'questions' => ['required', 'array', 'min:3'],
            'questions.*.question' => ['required', 'string', 'max:500'],
            'questions.*.correct' => ['required'],
            'questions.*.options' => ['required', 'array', 'min:2'],
            'questions.*.options.*' => ['nullable', 'string', 'max:255'],
        ], [
            'questions.min' => 'Each learning test must have at least 3 questions.',
        ]);

        DB::transaction(function () use ($roadmap, $validated) {
            $quiz = Quiz::updateOrCreate(
                ['roadmap_id' => $roadmap->id],
                ['title' => $validated['title'], 'passing_score' => $validated['passing_score']],
            );

            $quiz->questions()->delete(); // cascade removes options

            foreach (array_values($validated['questions']) as $q) {
                $question = QuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'question' => $q['question'],
                    'position' => 0,
                ]);

                $correctIndex = (int) $q['correct'];
                foreach (array_values($q['options']) as $oi => $optText) {
                    if ($optText === null || $optText === '') {
                        continue;
                    }
                    QuizOption::create([
                        'quiz_question_id' => $question->id,
                        'text' => $optText,
                        'is_correct' => $oi === $correctIndex,
                    ]);
                }
            }
        });

        return redirect()->route('admin.roadmaps.index')->with('success', 'Test saved for “'.$roadmap->title.'”.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\LessonProgress;
use App\Models\Progress;
use App\Models\Roadmap;
use App\Models\RoadmapStep;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function show(Roadmap $roadmap, RoadmapStep $step): View
    {
        abort_unless($step->roadmap_id === $roadmap->id, 404);

        $step->load('cards');

        $progress = $step->lessonProgressFor(auth()->user());
        $resume = $progress ? min($progress->last_card, max($step->cards->count() - 1, 0)) : 0;

        return view('lessons.show', [
            'roadmap' => $roadmap,
            'step' => $step,
            'cards' => $step->cards,
            'resume' => $resume,
            'alreadyCompleted' => $step->isCompletedBy(auth()->user()),
        ]);
    }

    public function save(Request $request, RoadmapStep $step): JsonResponse
    {
        $data = $request->validate([
            'last_card' => ['required', 'integer', 'min:0'],
            'completed' => ['sometimes', 'boolean'],
        ]);

        $user = $request->user();

        $lesson = LessonProgress::updateOrCreate(
            ['user_id' => $user->id, 'roadmap_step_id' => $step->id],
            [
                'last_card' => $data['last_card'],
                'completed_at' => ! empty($data['completed']) ? now() : LessonProgress::where('user_id', $user->id)->where('roadmap_step_id', $step->id)->value('completed_at'),
            ],
        );

        // Finishing the lesson also marks the roadmap step complete.
        if (! empty($data['completed'])) {
            Progress::updateOrCreate(
                ['user_id' => $user->id, 'roadmap_step_id' => $step->id],
                ['status' => 'completed', 'completed_at' => now()],
            );
        }

        return response()->json(['ok' => true, 'last_card' => $lesson->last_card]);
    }
}

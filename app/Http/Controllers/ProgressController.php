<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use App\Models\RoadmapStep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function toggle(Request $request, RoadmapStep $step): RedirectResponse
    {
        $user = $request->user();

        $existing = Progress::where('user_id', $user->id)
            ->where('roadmap_step_id', $step->id)
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            Progress::create([
                'user_id' => $user->id,
                'roadmap_step_id' => $step->id,
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }

        return back();
    }
}

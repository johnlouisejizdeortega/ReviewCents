<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(Request $request): View
    {
        return view('assignments.index', [
            'assignments' => Assignment::with('admin')
                ->where('user_id', $request->user()->id)
                ->latest()
                ->get(),
        ]);
    }

    public function submit(Request $request, Assignment $assignment): RedirectResponse
    {
        abort_unless($assignment->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'submission' => ['required', 'string', 'max:2000'],
        ]);

        $assignment->update([
            'submission' => $data['submission'],
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Your work has been submitted for review.');
    }
}

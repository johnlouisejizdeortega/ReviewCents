<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(): View
    {
        return view('admin.assignments.index', [
            'assignments' => Assignment::with(['user', 'admin'])->latest()->get(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.assignments.create', [
            'users' => User::where('role', '!=', 'admin')->orderBy('name')->get(),
            'selectedUser' => $request->get('user'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'type' => ['required', 'in:task,mission'],
            'due_date' => ['nullable', 'date'],
        ]);

        $data['assigned_by'] = $request->user()->id;
        $data['status'] = 'assigned';
        Assignment::create($data);

        return redirect()->route('admin.assignments.index')->with('success', 'Custom task assigned.');
    }

    public function review(Request $request, Assignment $assignment): RedirectResponse
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'feedback' => ['required', 'string', 'max:2000'],
        ]);

        $assignment->update([
            'rating' => $data['rating'],
            'feedback' => $data['feedback'],
            'status' => 'reviewed',
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Feedback saved.');
    }
}

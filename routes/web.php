<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoadmapController;
use App\Http\Controllers\ShowcaseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
Route::get('/resources/{resource}', [ResourceController::class, 'show'])->name('resources.show');

Route::get('/roadmaps', [RoadmapController::class, 'index'])->name('roadmaps.index');
Route::get('/roadmaps/{roadmap}', [RoadmapController::class, 'show'])->name('roadmaps.show');

Route::get('/challenges', [ChallengeController::class, 'index'])->name('challenges.index');
Route::get('/challenges/{challenge}', [ChallengeController::class, 'show'])->name('challenges.show');

Route::get('/showcase', [ShowcaseController::class, 'index'])->name('showcase.index');
Route::get('/showcase/{project}', [ShowcaseController::class, 'show'])->name('showcase.show');

Route::get('/u/{user:username}', [PublicProfileController::class, 'show'])->name('profile.show');

/*
|--------------------------------------------------------------------------
| Authenticated
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Reviews
    Route::post('/resources/{resource}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::patch('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Roadmap progress
    Route::post('/steps/{step}/toggle', [ProgressController::class, 'toggle'])->name('progress.toggle');

    // Flashcard lessons (per roadmap step) + resume position
    Route::get('/roadmaps/{roadmap}/steps/{step}/learn', [LessonController::class, 'show'])->name('lessons.show');
    Route::post('/steps/{step}/lesson-progress', [LessonController::class, 'save'])->name('lessons.save');

    // End-of-learning quiz
    Route::get('/roadmaps/{roadmap}/quiz', [QuizController::class, 'show'])->name('quiz.show');
    Route::post('/roadmaps/{roadmap}/quiz', [QuizController::class, 'submit'])->name('quiz.submit');

    // Challenge submissions
    Route::post('/challenges/{challenge}/submit', [ChallengeController::class, 'submit'])->name('challenges.submit');

    // Admin-assigned tasks (learner view)
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::post('/assignments/{assignment}/submit', [AssignmentController::class, 'submit'])->name('assignments.submit');

    // Showcase projects (owner CRUD)
    Route::resource('projects', ProjectController::class)->except(['show']);

    // Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/start/{user}', [ChatController::class, 'start'])->name('chat.start');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/messages', [ChatController::class, 'store'])->name('chat.messages.store');
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', Admin\CategoryController::class)->except(['show']);
    Route::resource('resources', Admin\ResourceController::class)->except(['show']);
    Route::resource('roadmaps', Admin\RoadmapController::class)->except(['show']);
    Route::resource('challenges', Admin\ChallengeController::class)->except(['show']);

    // Quiz management (per roadmap)
    Route::get('/roadmaps/{roadmap}/quiz', [Admin\QuizController::class, 'edit'])->name('roadmaps.quiz.edit');
    Route::put('/roadmaps/{roadmap}/quiz', [Admin\QuizController::class, 'update'])->name('roadmaps.quiz.update');

    // Users
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');

    // Assign custom tasks / missions + review
    Route::get('/assignments', [Admin\AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/create', [Admin\AssignmentController::class, 'create'])->name('assignments.create');
    Route::post('/assignments', [Admin\AssignmentController::class, 'store'])->name('assignments.store');
    Route::patch('/assignments/{assignment}/review', [Admin\AssignmentController::class, 'review'])->name('assignments.review');

    // Review challenge submissions (rate + feedback)
    Route::get('/submissions', [Admin\SubmissionController::class, 'index'])->name('submissions.index');
    Route::patch('/submissions/{submission}/review', [Admin\SubmissionController::class, 'review'])->name('submissions.review');
});

require __DIR__.'/auth.php';

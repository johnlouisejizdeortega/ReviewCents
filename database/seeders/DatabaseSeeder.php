<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Category;
use App\Models\Challenge;
use App\Models\ChallengeSubmission;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Progress;
use App\Models\Project;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use App\Models\Resource;
use App\Models\Review;
use App\Models\Roadmap;
use App\Models\RoadmapStep;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Users -------------------------------------------------------
        $admin = User::create([
            'name' => 'Admin Mentor',
            'username' => 'admin',
            'email' => 'admin@reviewcents.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'headline' => 'Lead mentor & platform admin',
            'bio' => 'I review learning paths and mentor upcoming developers.',
            'email_verified_at' => now(),
        ]);

        $demo = User::create([
            'name' => 'Demo Learner',
            'username' => 'demo',
            'email' => 'demo@reviewcents.test',
            'password' => Hash::make('password'),
            'role' => 'learner',
            'headline' => 'Aspiring full-stack developer',
            'bio' => 'Learning web development and design one roadmap at a time.',
            'email_verified_at' => now(),
        ]);

        $users = User::factory(6)->create();
        $allLearners = $users->push($demo);

        // ---- Categories --------------------------------------------------
        $categories = collect([
            ['name' => 'Web Development', 'icon' => '💻', 'description' => 'Frontend & backend web development.'],
            ['name' => 'Web Design', 'icon' => '🎨', 'description' => 'UI/UX, layout, and visual design.'],
            ['name' => 'Tools', 'icon' => '🛠️', 'description' => 'Editors, CLIs and developer tooling.'],
            ['name' => 'Bootcamps', 'icon' => '🚀', 'description' => 'Structured intensive programs.'],
        ])->map(fn ($c) => Category::create([
            'name' => $c['name'],
            'slug' => Str::slug($c['name']),
            'icon' => $c['icon'],
            'description' => $c['description'],
        ]));

        $webDev = $categories->firstWhere('name', 'Web Development');
        $webDesign = $categories->firstWhere('name', 'Web Design');
        $tools = $categories->firstWhere('name', 'Tools');

        // ---- Resources ---------------------------------------------------
        $resourceData = [
            ['The Modern JavaScript Bootcamp', $webDev, 'course', 'A complete guide to modern JS from fundamentals to advanced patterns.'],
            ['HTML & CSS Crash Course', $webDev, 'tutorial', 'Build responsive pages with semantic HTML and modern CSS.'],
            ['Laravel From Scratch', $webDev, 'course', 'Learn the Laravel framework by building real applications.'],
            ['Figma for Developers', $webDesign, 'tutorial', 'Turn designs into code using Figma effectively.'],
            ['Refactoring UI', $webDesign, 'book', 'Practical design tips for developers who build interfaces.'],
            ['Tailwind CSS Mastery', $webDesign, 'course', 'Design beautiful, responsive UIs with utility-first CSS.'],
            ['VS Code Power User', $tools, 'tutorial', 'Supercharge your editor workflow.'],
            ['Git & GitHub Essentials', $tools, 'course', 'Version control fundamentals every developer needs.'],
        ];

        $resources = collect($resourceData)->map(function ($r) use ($admin) {
            return Resource::create([
                'category_id' => $r[1]->id,
                'submitted_by' => $admin->id,
                'title' => $r[0],
                'slug' => Str::slug($r[0]),
                'description' => $r[3],
                'url' => 'https://example.com/'.Str::slug($r[0]),
                'type' => $r[2],
            ]);
        });

        // ---- Reviews (and recompute avg) ---------------------------------
        foreach ($resources as $resource) {
            $reviewers = $allLearners->random(min(4, $allLearners->count()));
            foreach ($reviewers as $reviewer) {
                Review::create([
                    'user_id' => $reviewer->id,
                    'resource_id' => $resource->id,
                    'rating' => rand(3, 5),
                    'body' => 'Really helpful resource, learned a lot. Would recommend to other learners.',
                ]);
            }
            $resource->recalculateRating();
        }

        // ---- Roadmaps + steps + quizzes ----------------------------------
        $this->createRoadmap(
            'Frontend Web Developer',
            $webDev,
            'beginner',
            'Go from zero to a job-ready frontend developer.',
            [
                ['Learn HTML', 'Structure content with semantic HTML.'],
                ['Learn CSS & Responsive Design', 'Style and make layouts responsive.'],
                ['Learn JavaScript', 'Add interactivity and logic.'],
                ['Learn a Framework', 'Pick React, Vue, or similar.'],
            ],
            $resources,
            [
                [
                    'q' => 'Which HTML tag is used for the largest heading?',
                    'options' => [['<h1>', true], ['<h6>', false], ['<head>', false], ['<big>', false]],
                ],
                [
                    'q' => 'Which CSS property controls text color?',
                    'options' => [['color', true], ['font-style', false], ['text-align', false], ['background', false]],
                ],
                [
                    'q' => 'What does the JavaScript "===" operator do?',
                    'options' => [['Strict equality comparison', true], ['Assignment', false], ['Loose equality', false], ['Negation', false]],
                ],
                [
                    'q' => 'Which of these is a popular JS framework?',
                    'options' => [['React', true], ['Django', false], ['Laravel', false], ['Flask', false]],
                ],
            ],
            $allLearners,
        );

        $this->createRoadmap(
            'Web Design Fundamentals',
            $webDesign,
            'beginner',
            'Learn the core principles of designing for the web.',
            [
                ['Design Principles', 'Contrast, alignment, hierarchy, spacing.'],
                ['Color & Typography', 'Choosing palettes and type scales.'],
                ['Layout & Grids', 'Composing balanced responsive layouts.'],
            ],
            $resources,
            [
                [
                    'q' => 'Which principle helps guide the user\'s eye through a page?',
                    'options' => [['Visual hierarchy', true], ['Minification', false], ['Hoisting', false], ['Caching', false]],
                ],
                [
                    'q' => 'What is a good practice for readable body text?',
                    'options' => [['Sufficient line-height & contrast', true], ['Tiny gray text', false], ['All caps everywhere', false], ['No spacing', false]],
                ],
                [
                    'q' => 'Which unit is best for responsive typography?',
                    'options' => [['rem/em', true], ['px only', false], ['pt only', false], ['cm', false]],
                ],
            ],
            $allLearners,
        );

        // ---- Challenges & missions ---------------------------------------
        $challenges = collect([
            ['Build a Responsive Navbar', $webDev, 'easy', 'challenge', 'Create a mobile-first navigation bar with a hamburger menu.'],
            ['Clone a Landing Page', $webDesign, 'medium', 'challenge', 'Recreate a landing page of your choice pixel-perfect.'],
            ['Build a To-Do App', $webDev, 'medium', 'mission', 'Full CRUD to-do app with local storage or a backend.'],
            ['Design a Color System', $webDesign, 'hard', 'mission', 'Create an accessible color system with light and dark modes.'],
        ])->map(fn ($c) => Challenge::create([
            'category_id' => $c[1]->id,
            'title' => $c[0],
            'slug' => Str::slug($c[0]),
            'description' => $c[4],
            'difficulty' => $c[2],
            'type' => $c[3],
            'points' => ['easy' => 10, 'medium' => 25, 'hard' => 50][$c[2]],
        ]));

        // A reviewed challenge submission by the demo user
        ChallengeSubmission::create([
            'user_id' => $demo->id,
            'challenge_id' => $challenges->first()->id,
            'submission_url' => 'https://github.com/demo/responsive-navbar',
            'notes' => 'Built with Tailwind, fully responsive with Alpine toggle.',
            'status' => 'reviewed',
            'rating' => 4,
            'feedback' => 'Great work! Consider adding keyboard accessibility to the menu.',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);

        // ---- Admin-assigned custom tasks ---------------------------------
        Assignment::create([
            'assigned_by' => $admin->id,
            'user_id' => $demo->id,
            'title' => 'Rebuild your portfolio homepage',
            'description' => 'Apply the design principles from the Web Design roadmap to rebuild your portfolio hero section. Submit a link when done.',
            'type' => 'mission',
            'due_date' => now()->addDays(7),
            'status' => 'assigned',
        ]);

        Assignment::create([
            'assigned_by' => $admin->id,
            'user_id' => $demo->id,
            'title' => 'Accessibility audit',
            'description' => 'Run an accessibility audit on any project and list 5 improvements.',
            'type' => 'task',
            'due_date' => now()->subDays(2),
            'status' => 'reviewed',
            'submission' => 'https://github.com/demo/a11y-audit',
            'submitted_at' => now()->subDays(3),
            'rating' => 5,
            'feedback' => 'Excellent, thorough audit. Nicely prioritized fixes.',
            'reviewed_at' => now()->subDay(),
        ]);

        // ---- Projects (showcase) -----------------------------------------
        Project::create([
            'user_id' => $demo->id,
            'title' => 'Weather Dashboard',
            'description' => 'A responsive weather app consuming a public API.',
            'live_url' => 'https://example.com/weather',
            'repo_url' => 'https://github.com/demo/weather',
            'tags' => 'JavaScript, API, CSS',
        ]);

        foreach ($users->take(3) as $u) {
            Project::create([
                'user_id' => $u->id,
                'title' => 'Portfolio Site',
                'description' => 'Personal portfolio built while learning on ReviewCents.',
                'live_url' => 'https://example.com/'.$u->username,
                'repo_url' => 'https://github.com/'.$u->username.'/portfolio',
                'tags' => 'HTML, CSS, Tailwind',
            ]);
        }

        // ---- Chat conversation -------------------------------------------
        $conversation = Conversation::create(['is_group' => false]);
        $conversation->users()->attach([$admin->id, $demo->id]);

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $admin->id,
            'body' => 'Hi! Welcome to ReviewCents. How is the Frontend roadmap going?',
        ]);
        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $demo->id,
            'body' => 'Going well! Just passed the JavaScript quiz. 🎉',
        ]);
        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $admin->id,
            'body' => 'Awesome. I assigned you a portfolio mission — check your dashboard.',
        ]);
    }

    /**
     * Helper to create a roadmap with ordered steps and an end-of-learning quiz.
     * The quiz always has >= 3 questions (enforced by an assertion).
     */
    private function createRoadmap(
        string $title,
        Category $category,
        string $level,
        string $description,
        array $steps,
        $resources,
        array $questions,
        $learners,
    ): Roadmap {
        if (count($questions) < 3) {
            throw new \RuntimeException("Quiz for [$title] must have at least 3 questions.");
        }

        $roadmap = Roadmap::create([
            'category_id' => $category->id,
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $description,
            'level' => $level,
        ]);

        $categoryResources = $resources->where('category_id', $category->id)->values();

        $createdSteps = collect($steps)->map(function ($s, $i) use ($roadmap, $categoryResources) {
            return RoadmapStep::create([
                'roadmap_id' => $roadmap->id,
                'resource_id' => $categoryResources->get($i)?->id,
                'title' => $s[0],
                'description' => $s[1],
                'position' => $i + 1,
            ]);
        });

        $quiz = Quiz::create([
            'roadmap_id' => $roadmap->id,
            'title' => $title.' — Final Test',
            'description' => 'Test what you learned in this roadmap.',
            'passing_score' => 60,
        ]);

        foreach ($questions as $qi => $q) {
            $question = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $q['q'],
                'position' => $qi + 1,
            ]);
            foreach ($q['options'] as $opt) {
                QuizOption::create([
                    'quiz_question_id' => $question->id,
                    'text' => $opt[0],
                    'is_correct' => $opt[1],
                ]);
            }
        }

        // Give the demo learner some progress + a passing attempt.
        $demo = $learners->firstWhere('username', 'demo');
        if ($demo) {
            foreach ($createdSteps->take(2) as $step) {
                Progress::create([
                    'user_id' => $demo->id,
                    'roadmap_step_id' => $step->id,
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
            }

            QuizAttempt::create([
                'user_id' => $demo->id,
                'quiz_id' => $quiz->id,
                'score' => 75,
                'correct_count' => (int) round(count($questions) * 0.75),
                'total_count' => count($questions),
                'passed' => true,
                'completed_at' => now(),
            ]);
        }

        return $roadmap;
    }
}

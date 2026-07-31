<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Category;
use App\Models\Challenge;
use App\Models\ChallengeSubmission;
use App\Models\Conversation;
use App\Models\LessonCard;
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
        // NOTE: no factories/Faker here — this seeder must run in production
        // (Laravel Cloud installs with --no-dev, so Faker is unavailable).
        $admin = User::firstOrCreate(
            ['email' => 'admin@reviewcents.test'],
            [
                'name' => 'Admin Mentor',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'headline' => 'Lead mentor & platform admin',
                'bio' => 'I review learning paths and mentor upcoming developers.',
                'email_verified_at' => now(),
            ],
        );

        $demo = User::firstOrCreate(
            ['email' => 'demo@reviewcents.test'],
            [
                'name' => 'Demo Learner',
                'username' => 'demo',
                'password' => Hash::make('password'),
                'role' => 'learner',
                'headline' => 'Aspiring full-stack developer',
                'bio' => 'Learning web development and design one roadmap at a time.',
                'email_verified_at' => now(),
            ],
        );

        $sampleUsers = [
            ['Alex Rivera', 'developer'],
            ['Sam Chen', 'learner'],
            ['Jordan Lee', 'developer'],
            ['Taylor Kim', 'learner'],
            ['Morgan Diaz', 'developer'],
            ['Casey Park', 'learner'],
        ];

        $users = collect($sampleUsers)->map(function ($u) {
            $username = Str::slug($u[0]);

            return User::firstOrCreate(
                ['email' => $username.'@reviewcents.test'],
                [
                    'name' => $u[0],
                    'username' => $username,
                    'password' => Hash::make('password'),
                    'role' => $u[1],
                    'headline' => $u[1] === 'developer' ? 'Developer & mentor' : 'Learning to code',
                    'email_verified_at' => now(),
                ],
            );
        });

        $allLearners = $users->push($demo);

        // ---- Categories --------------------------------------------------
        $categories = collect([
            ['name' => 'Web Development', 'description' => 'Frontend & backend web development.'],
            ['name' => 'Web Design', 'description' => 'UI/UX, layout, and visual design.'],
            ['name' => 'Tools', 'description' => 'Editors, CLIs and developer tooling.'],
            ['name' => 'Bootcamps', 'description' => 'Structured intensive programs.'],
        ])->map(fn ($c) => Category::create([
            'name' => $c['name'],
            'slug' => Str::slug($c['name']),
            'description' => $c['description'],
        ]));

        $webDev = $categories->firstWhere('name', 'Web Development');
        $webDesign = $categories->firstWhere('name', 'Web Design');
        $tools = $categories->firstWhere('name', 'Tools');

        // ---- Resources ---------------------------------------------------
        $resourceData = [
            ['The Modern JavaScript Guide', $webDev, 'course', 'A complete guide to modern JS from fundamentals to advanced patterns.', 'https://javascript.info'],
            ['HTML & CSS Crash Course', $webDev, 'tutorial', 'Build responsive pages with semantic HTML and modern CSS.', 'https://web.dev/learn/html'],
            ['Laravel From Scratch', $webDev, 'course', 'Learn the Laravel framework by building real applications.', 'https://laravel.com/docs'],
            ['Figma for Developers', $webDesign, 'tutorial', 'Turn designs into code using Figma effectively.', 'https://help.figma.com'],
            ['Refactoring UI', $webDesign, 'book', 'Practical design tips for developers who build interfaces.', 'https://www.refactoringui.com'],
            ['Tailwind CSS Docs', $webDesign, 'course', 'Design beautiful, responsive UIs with utility-first CSS.', 'https://tailwindcss.com/docs'],
            ['VS Code Docs', $tools, 'tutorial', 'Supercharge your editor workflow.', 'https://code.visualstudio.com/docs'],
            ['Git & GitHub Essentials', $tools, 'course', 'Version control fundamentals every developer needs.', 'https://docs.github.com/get-started'],
        ];

        $resources = collect($resourceData)->map(function ($r) use ($admin) {
            return Resource::create([
                'category_id' => $r[1]->id,
                'submitted_by' => $admin->id,
                'title' => $r[0],
                'slug' => Str::slug($r[0]),
                'description' => $r[3],
                'url' => $r[4],
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
                ['Learn HTML', 'Structure content with semantic HTML.', [
                    ['What is HTML?', 'HTML (HyperText Markup Language) is the skeleton of every web page. It uses tags like <p>, <h1>, and <a> to give content structure and meaning — not styling.', 'Structure, not style'],
                    ['Semantic elements', 'Semantic tags describe their meaning: <header>, <nav>, <main>, <article>, <footer>. They make pages more accessible to screen readers and easier for search engines to understand.', null],
                    ['Headings <h1>–<h6>', 'Use one <h1> per page for the main title, then <h2>–<h6> for a logical outline. Never pick a heading level just for its size — style with CSS instead.', null],
                    ['Links & images', 'An anchor <a href="/about">About</a> creates a link; <img src="cat.jpg" alt="A cat"> embeds an image. Always add descriptive alt text for accessibility.', 'alt text matters'],
                    ['Forms', 'Collect input with <form>, <input>, <label>, and <button>. Pair every input with a <label> so it is usable and accessible.', null],
                ]],
                ['Learn CSS & Responsive Design', 'Style and make layouts responsive.', [
                    ['What is CSS?', 'CSS (Cascading Style Sheets) controls how HTML looks — colours, spacing, fonts, layout. You select elements and apply rules: selector { property: value; }', null],
                    ['The box model', 'Every element is a box with four layers: content → padding → border → margin. Understanding these is the key to controlling spacing.', 'content, padding, border, margin'],
                    ['Flexbox', 'display: flex turns a container into a flexible row (or column with flex-direction: column). Use justify-content and align-items to position children.', null],
                    ['Media queries', 'Responsive design adapts to screen size. @media (max-width: 640px) { … } applies styles only on small screens. Design mobile-first, then add breakpoints.', 'mobile-first'],
                    ['Units: rem vs px', 'px is fixed; rem scales with the root font size, making layouts more accessible and consistent. Prefer rem/em for typography and spacing.', null],
                ]],
                ['Learn JavaScript', 'Add interactivity and logic.', [
                    ['What is JavaScript?', 'JavaScript makes pages interactive — responding to clicks, updating content, fetching data. It runs in the browser and, via Node.js, on servers too.', null],
                    ['Variables: let & const', 'Use const for values that never change and let for values that do. Avoid var. Example: const name = "Ada"; let count = 0;', 'const by default'],
                    ['Functions', 'Functions package reusable logic. Arrow syntax: const add = (a, b) => a + b; Call it with add(2, 3) and it returns 5.', null],
                    ['=== vs ==', 'Triple equals compares value AND type with no surprises; double equals converts types first and causes bugs. Always use ===.', 'always ==='],
                    ['The DOM', 'The DOM is the browser live tree of your HTML. document.querySelector(".btn").addEventListener("click", …) lets JS react to user actions.', null],
                ]],
                ['Learn a Framework', 'Pick React, Vue, or similar.', [
                    ['Why a framework?', 'Frameworks like React, Vue, and Svelte help you build complex UIs from reusable components and keep the screen in sync with your data automatically.', null],
                    ['Components', 'A component is a self-contained piece of UI (a button, a card) with its own markup and logic. You compose small components into whole pages.', null],
                    ['State', 'State is data that can change over time (a counter, a form input). When state updates, the framework re-renders the affected UI for you.', 'data that changes'],
                    ['Props', 'Props pass data from a parent component down to a child, making components configurable and reusable.', null],
                    ['Pick one & go deep', 'Do not chase every framework. Learn one well — React is the most in-demand — and the concepts transfer to the others.', null],
                ]],
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
                ['Design Principles', 'Contrast, alignment, hierarchy, spacing.', [
                    ['Visual hierarchy', 'Hierarchy guides the eye to what matters most first. Create it with size, weight, colour, and spacing — the most important thing should be the most prominent.', null],
                    ['Contrast', 'Contrast makes elements distinct and text readable. Ensure enough contrast between text and background, and between primary and secondary actions.', 'accessibility'],
                    ['Alignment', 'Align elements to a shared grid or edge. Strong alignment looks intentional and tidy; random positions look messy.', null],
                    ['Whitespace', 'Empty space is not wasted — it groups related items, separates unrelated ones, and gives content room to breathe. When in doubt, add more.', 'less is more'],
                    ['Consistency', 'Reuse the same spacing, colours, and components everywhere. Consistency builds trust and makes interfaces feel polished and learnable.', null],
                ]],
                ['Color & Typography', 'Choosing palettes and type scales.', [
                    ['Building a palette', 'Start with one primary colour, a neutral scale (greys), and one accent. Limit your palette — too many colours feels chaotic.', null],
                    ['Contrast & accessibility', 'Body text needs a contrast ratio of at least 4.5:1 against its background. Test it — low-contrast grey-on-white excludes many readers.', '4.5:1'],
                    ['Type scale', 'Use a consistent set of font sizes (e.g. 12, 14, 16, 20, 24, 32) instead of arbitrary values. A scale creates rhythm and hierarchy.', null],
                    ['Readable body text', 'Keep line length around 60–75 characters, line-height about 1.5, and body size at least 16px. Comfort beats cramming.', null],
                    ['Pairing fonts', 'One typeface used well is plenty. If you pair, contrast roles: a characterful heading font with a neutral, legible body font.', null],
                ]],
                ['Layout & Grids', 'Composing balanced responsive layouts.', [
                    ['Grids', 'A grid divides the page into columns for consistent alignment. Even a simple 12-column grid makes layouts feel balanced and organised.', null],
                    ['Responsive layout', 'Design mobile-first: start with a single column, then let content reflow into multiple columns as the screen grows.', 'mobile-first'],
                    ['Spacing system', 'Use a spacing scale (4, 8, 12, 16, 24, 32px). Consistent gaps look far more professional than eyeballed values.', null],
                    ['Proximity', 'Group related items close together and separate unrelated groups with more space. Proximity communicates relationships before anyone reads a word.', null],
                    ['Focal point', 'Give each screen one clear focal point — the primary action or message. Everything else should support, not compete.', null],
                ]],
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
            $step = RoadmapStep::create([
                'roadmap_id' => $roadmap->id,
                'resource_id' => $categoryResources->get($i)?->id,
                'title' => $s[0],
                'description' => $s[1],
                'position' => $i + 1,
            ]);

            // Flashcards for this step (front, back, optional hint).
            foreach (($s[2] ?? []) as $ci => $card) {
                LessonCard::create([
                    'roadmap_step_id' => $step->id,
                    'front' => $card[0],
                    'back' => $card[1],
                    'hint' => $card[2] ?? null,
                    'position' => $ci + 1,
                ]);
            }

            return $step;
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

            // An in-progress lesson on the 3rd step so "Continue where you left off" shows.
            if ($third = $createdSteps->get(2)) {
                \App\Models\LessonProgress::create([
                    'user_id' => $demo->id,
                    'roadmap_step_id' => $third->id,
                    'last_card' => 1,
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

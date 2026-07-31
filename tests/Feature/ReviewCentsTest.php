<?php

namespace Tests\Feature;

use App\Events\MessageSent;
use App\Models\Assignment;
use App\Models\Category;
use App\Models\Challenge;
use App\Models\Conversation;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use App\Models\Resource;
use App\Models\Roadmap;
use App\Models\RoadmapStep;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ReviewCentsTest extends TestCase
{
    use RefreshDatabase;

    private function category(): Category
    {
        return Category::create(['name' => 'Web Development', 'slug' => 'web-development']);
    }

    public function test_public_pages_load(): void
    {
        $this->get('/')->assertOk();
        $this->get('/resources')->assertOk();
        $this->get('/roadmaps')->assertOk();
        $this->get('/challenges')->assertOk();
        $this->get('/showcase')->assertOk();
    }

    public function test_user_can_review_resource_and_average_updates(): void
    {
        $user = User::factory()->create();
        $resource = Resource::create([
            'category_id' => $this->category()->id,
            'title' => 'Test Course', 'slug' => 'test-course',
            'description' => 'Desc', 'type' => 'course',
        ]);

        $this->actingAs($user)->post(route('reviews.store', $resource), [
            'rating' => 4,
            'body' => 'Great',
        ])->assertRedirect();

        $this->assertDatabaseHas('reviews', ['resource_id' => $resource->id, 'rating' => 4]);
        $this->assertEquals(4.0, $resource->fresh()->avg_rating);
        $this->assertEquals(1, $resource->fresh()->reviews_count);
    }

    public function test_quiz_scoring_records_passing_attempt(): void
    {
        $user = User::factory()->create();
        $roadmap = Roadmap::create([
            'category_id' => $this->category()->id,
            'title' => 'FE', 'slug' => 'fe', 'description' => 'd',
        ]);
        $quiz = Quiz::create(['roadmap_id' => $roadmap->id, 'title' => 'T', 'passing_score' => 60]);

        $answers = [];
        foreach (range(1, 3) as $n) {
            $q = QuizQuestion::create(['quiz_id' => $quiz->id, 'question' => "Q$n", 'position' => $n]);
            $correct = QuizOption::create(['quiz_question_id' => $q->id, 'text' => 'right', 'is_correct' => true]);
            QuizOption::create(['quiz_question_id' => $q->id, 'text' => 'wrong', 'is_correct' => false]);
            $answers[$q->id] = $correct->id;
        }

        $this->actingAs($user)
            ->post(route('quiz.submit', $roadmap), ['answers' => $answers])
            ->assertOk();

        $attempt = $user->quizAttempts()->first();
        $this->assertNotNull($attempt);
        $this->assertEquals(100, $attempt->score);
        $this->assertTrue($attempt->passed);
    }

    public function test_roadmap_step_progress_toggles(): void
    {
        $user = User::factory()->create();
        $roadmap = Roadmap::create([
            'category_id' => $this->category()->id,
            'title' => 'R', 'slug' => 'r', 'description' => 'd',
        ]);
        $step = RoadmapStep::create(['roadmap_id' => $roadmap->id, 'title' => 'S', 'position' => 1]);

        $this->actingAs($user)->post(route('progress.toggle', $step))->assertRedirect();
        $this->assertDatabaseHas('progress', ['user_id' => $user->id, 'roadmap_step_id' => $step->id]);

        $this->actingAs($user)->post(route('progress.toggle', $step))->assertRedirect();
        $this->assertDatabaseMissing('progress', ['user_id' => $user->id, 'roadmap_step_id' => $step->id]);
    }

    public function test_admin_can_assign_and_rate_task(): void
    {
        $admin = User::factory()->admin()->create();
        $learner = User::factory()->create();

        $this->actingAs($admin)->post(route('admin.assignments.store'), [
            'user_id' => $learner->id,
            'title' => 'Build a nav',
            'description' => 'Do it',
            'type' => 'task',
        ])->assertRedirect();

        $assignment = Assignment::first();
        $this->assertEquals($learner->id, $assignment->user_id);

        $this->actingAs($admin)->patch(route('admin.assignments.review', $assignment), [
            'rating' => 5,
            'feedback' => 'Nice work',
        ])->assertRedirect();

        $assignment->refresh();
        $this->assertEquals(5, $assignment->rating);
        $this->assertEquals('reviewed', $assignment->status);
    }

    public function test_non_admin_cannot_access_admin(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_admin_quiz_requires_at_least_three_questions(): void
    {
        $admin = User::factory()->admin()->create();
        $roadmap = Roadmap::create([
            'category_id' => $this->category()->id,
            'title' => 'R', 'slug' => 'r', 'description' => 'd',
        ]);

        $payload = [
            'title' => 'Test',
            'passing_score' => 60,
            'questions' => [
                ['question' => 'Q1', 'correct' => 0, 'options' => ['a', 'b']],
                ['question' => 'Q2', 'correct' => 0, 'options' => ['a', 'b']],
            ],
        ];

        $this->actingAs($admin)
            ->from(route('admin.roadmaps.quiz.edit', $roadmap))
            ->put(route('admin.roadmaps.quiz.update', $roadmap), $payload)
            ->assertSessionHasErrors('questions');
    }

    public function test_sending_a_message_broadcasts_event(): void
    {
        Event::fake([MessageSent::class]);

        $a = User::factory()->create();
        $b = User::factory()->create();
        $conversation = Conversation::create(['is_group' => false]);
        $conversation->users()->attach([$a->id, $b->id]);

        $this->actingAs($a)
            ->postJson(route('chat.messages.store', $conversation), ['body' => 'Hello'])
            ->assertOk();

        $this->assertDatabaseHas('messages', ['conversation_id' => $conversation->id, 'body' => 'Hello']);
        Event::assertDispatched(MessageSent::class);
    }

    public function test_challenge_submission_flow(): void
    {
        $user = User::factory()->create();
        $challenge = Challenge::create([
            'title' => 'Nav', 'slug' => 'nav', 'description' => 'd',
            'difficulty' => 'easy', 'type' => 'challenge', 'points' => 10,
        ]);

        $this->actingAs($user)->post(route('challenges.submit', $challenge), [
            'submission_url' => 'https://github.com/x/y',
            'notes' => 'done',
        ])->assertRedirect();

        $this->assertDatabaseHas('challenge_submissions', [
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'status' => 'pending',
        ]);
    }
}

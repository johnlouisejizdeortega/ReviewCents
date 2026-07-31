<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $conversations = $user->conversations()
            ->with(['users', 'latestMessage'])
            ->get()
            ->sortByDesc(fn ($c) => $c->messages->max('created_at') ?? $c->created_at)
            ->values();

        // People the user can start chatting with.
        $people = User::where('id', '!=', $user->id)->orderBy('name')->take(50)->get();

        return view('chat.index', [
            'conversations' => $conversations,
            'people' => $people,
        ]);
    }

    public function start(Request $request, User $user): RedirectResponse
    {
        $me = $request->user();

        abort_if($user->id === $me->id, 400);

        // Find an existing 1:1 conversation between the two users.
        $conversation = Conversation::where('is_group', false)
            ->whereHas('users', fn ($q) => $q->where('users.id', $me->id))
            ->whereHas('users', fn ($q) => $q->where('users.id', $user->id))
            ->first();

        if (! $conversation) {
            $conversation = Conversation::create(['is_group' => false]);
            $conversation->users()->attach([$me->id, $user->id]);
        }

        return redirect()->route('chat.show', $conversation);
    }

    public function show(Request $request, Conversation $conversation): View
    {
        $this->authorizeMember($request, $conversation);

        $conversation->load(['users', 'messages.user']);

        return view('chat.show', [
            'conversation' => $conversation,
            'messages' => $conversation->messages,
        ]);
    }

    public function store(Request $request, Conversation $conversation): JsonResponse|RedirectResponse
    {
        $this->authorizeMember($request, $conversation);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        $message->load('user');

        broadcast(new MessageSent($message))->toOthers();

        if ($request->wantsJson()) {
            return response()->json([
                'id' => $message->id,
                'body' => $message->body,
                'user_id' => $message->user_id,
                'user_name' => $message->user->name,
                'created_at' => $message->created_at->toDateTimeString(),
            ]);
        }

        return back();
    }

    private function authorizeMember(Request $request, Conversation $conversation): void
    {
        abort_unless(
            $conversation->users()->where('users.id', $request->user()->id)->exists(),
            403
        );
    }
}

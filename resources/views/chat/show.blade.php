<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('chat.index') }}" class="text-sm text-gray-900 dark:text-white hover:underline">←</a>
            <h1 class="text-lg font-bold">{{ $conversation->titleFor(auth()->user()) }}</h1>
        </div>

        <x-card class="flex flex-col h-[70vh]"
                x-data="chatRoom({
                    conversationId: {{ $conversation->id }},
                    meId: {{ auth()->id() }},
                    postUrl: '{{ route('chat.messages.store', $conversation) }}',
                    csrf: '{{ csrf_token() }}'
                })">
            {{-- Messages --}}
            <div x-ref="messages" class="flex-1 overflow-y-auto p-4 space-y-3">
                @foreach ($messages as $message)
                    <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[75%]">
                            @if ($message->user_id !== auth()->id())
                                <p class="text-xs text-gray-400 mb-0.5">{{ $message->user->name }}</p>
                            @endif
                            <div class="px-4 py-2 rounded-2xl text-sm {{ $message->user_id === auth()->id() ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900 rounded-br-sm' : 'bg-gray-100 dark:bg-gray-800 rounded-bl-sm' }}">
                                {{ $message->body }}
                            </div>
                        </div>
                    </div>
                @endforeach
                {{-- Live messages appended here --}}
                <template x-for="msg in liveMessages" :key="msg.id">
                    <div class="flex" :class="msg.user_id === meId ? 'justify-end' : 'justify-start'">
                        <div class="max-w-[75%]">
                            <p class="text-xs text-gray-400 mb-0.5" x-show="msg.user_id !== meId" x-text="msg.user_name"></p>
                            <div class="px-4 py-2 rounded-2xl text-sm" :class="msg.user_id === meId ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900 rounded-br-sm' : 'bg-gray-100 dark:bg-gray-800 rounded-bl-sm'" x-text="msg.body"></div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Composer --}}
            <form @submit.prevent="send" class="border-t border-gray-200 dark:border-gray-800 p-3 flex gap-2">
                <input x-model="draft" type="text" placeholder="Type a message…" required
                       class="flex-1 rounded-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-gray-900 dark:focus:border-white focus:ring-gray-900 dark:focus:ring-white">
                <button type="submit" class="px-4 py-2 rounded-full bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-sm font-semibold hover:bg-gray-700 dark:hover:bg-gray-200">Send</button>
            </form>
        </x-card>
        <p class="mt-2 text-xs text-gray-400 text-center">Messages update live when Reverb is running (<code>php artisan reverb:start</code>).</p>
    </div>

    @push('head')
    <script>
        function chatRoom(config) {
            return {
                draft: '',
                liveMessages: [],
                meId: config.meId,
                init() {
                    this.scrollToBottom();
                    // Subscribe to the private conversation channel if Echo is available.
                    if (window.Echo) {
                        window.Echo.private(`conversation.${config.conversationId}`)
                            .listen('.message.sent', (e) => {
                                this.liveMessages.push(e);
                                this.$nextTick(() => this.scrollToBottom());
                            });
                    }
                },
                async send() {
                    if (!this.draft.trim()) return;
                    const body = this.draft;
                    this.draft = '';
                    const res = await fetch(config.postUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': config.csrf,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ body }),
                    });
                    if (res.ok) {
                        const msg = await res.json();
                        this.liveMessages.push(msg);
                        this.$nextTick(() => this.scrollToBottom());
                    }
                },
                scrollToBottom() {
                    const el = this.$refs.messages;
                    if (el) el.scrollTop = el.scrollHeight;
                },
            };
        }
    </script>
    @endpush
</x-app-layout>

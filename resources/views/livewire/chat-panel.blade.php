<div class="fixed inset-x-0 bottom-0 z-50 p-4 sm:inset-x-auto sm:bottom-6 sm:right-6"
    x-data="{
        open: false,
        isMaximized: false,
        defaultWidth: 384,
        defaultHeight: 512,
        toggleMaximize() {
            this.isMaximized = !this.isMaximized;
        }
    }">

    <!-- Chat Window -->
    <div x-cloak x-show="open"
        x-transition:enter="transition ease-out duration-200"
        :style="isMaximized ? 'width: ' + (defaultWidth * 2) + 'px; height: ' + (defaultHeight * 2) + 'px;' : 'width: ' + defaultWidth + 'px; height: ' + defaultHeight + 'px;'"
        x-transition:enter-start="opacity-0 translate-y-3 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-3 scale-95"
        class="mb-4 flex h-[32rem] w-full flex-col overflow-hidden rounded-[1.5rem] border border-gray-200/80 bg-white/95 shadow-[0_20px_60px_-15px_rgba(15,23,42,0.35)] backdrop-blur sm:w-[24rem] dark:border-gray-700 dark:bg-gray-900/95">

        <!-- Chat Header -->
        <div class="flex items-center justify-between border-b border-gray-200/80 bg-gradient-to-r from-primary-50/70 to-white px-4 py-3 dark:border-gray-700 dark:from-gray-800/80 dark:to-gray-900">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-primary-500 text-white shadow-sm ring-1 ring-primary-400/30">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="currentColor">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M8.959 1.99l-.147 .028l-.115 .029a1 1 0 0 0 -.646 1.27l.749 2.245l-2.815 1.735a2 2 0 0 0 -.655 2.751l.089 .133a2 2 0 0 0 1.614 .819l1.563 -.001l-1.614 4.674a1 1 0 0 0 .945 1.327h7.961a1 1 0 0 0 1 -.978l.112 -5c0 -3.827 -1.555 -6.878 -4.67 -7.966l-2.399 -.83l-.375 -.121l-.258 -.074l-.135 -.031l-.101 -.013l-.055 -.001l-.048 .003z" />
                        <path
                            d="M18 18h-12a1 1 0 0 0 -1 1a2 2 0 0 0 2 2h10a2 2 0 0 0 1.987 -1.768l.011 -.174a1 1 0 0 0 -.998 -1.058z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">AI Assistant</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Connected</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 shadow-sm transition hover:bg-gray-50 hover:text-gray-900 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white"
                    @click="toggleMaximize()"
                    :title="isMaximized ? 'Restore chat' : 'Maximize chat'"
                    :aria-label="isMaximized ? 'Restore chat' : 'Maximize chat'">
                    <svg x-show="!isMaximized" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 3H5a2 2 0 0 0-2 2v3" />
                        <path d="M21 8V5a2 2 0 0 0-2-2h-3" />
                        <path d="M3 16v3a2 2 0 0 0 2 2h3" />
                        <path d="M16 21h3a2 2 0 0 0 2-2v-3" />
                    </svg>
                    <svg x-cloak x-show="isMaximized" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 3v3a2 2 0 0 1-2 2H3" />
                        <path d="M21 8h-3a2 2 0 0 1-2-2V3" />
                        <path d="M3 16h3a2 2 0 0 1 2 2v3" />
                        <path d="M16 21v-3a2 2 0 0 1 2-2h3" />
                    </svg>
                </button>
                <x-filament::button color="gray" size="sm" wire:click="clearChat" icon="heroicon-o-trash"
                    tooltip="Clear chat" />
            </div>
        </div>

        <!-- Messages Container -->
        <div class="flex-1 space-y-4 overflow-y-auto bg-gradient-to-b from-gray-50/70 via-white to-gray-50/70 p-4 dark:from-gray-900/70 dark:via-gray-900 dark:to-gray-900" id="messages-container" role="log" aria-live="polite"
            aria-relevant="additions" aria-atomic="false">
            @foreach ($messages as $message)
                <div class="mb-4 {{ $message['is_user'] ? 'flex justify-end' : '' }}" id="msg-{{ $message['id'] }}"
                    aria-label="{{ $message['is_user'] ? 'You' : 'Assistant' }} at {{ $message['timestamp'] }}">
                    @if ($message['is_user'])
                        <!-- User Message -->
                        <div class="max-w-xs lg:max-w-md">
                            <div class="max-w-[85%] rounded-2xl bg-primary-500 p-3 text-white shadow-sm">
                                <div class="prose prose-sm max-w-none break-words text-sm text-white prose-headings:text-white prose-strong:text-white prose-code:text-white prose-a:text-white dark:prose-invert">
                                    {!! Illuminate\Support\Str::of($message['content'])->markdown() !!}
                                </div>
                            </div>
                            <p class="mt-1 text-right text-xs text-gray-500 dark:text-gray-400">
                                {{ $message['timestamp'] }}
                            </p>
                        </div>
                    @else
                        <!-- Bot Message -->
                        <div class="max-w-xs lg:max-w-md">
                            <div class="flex items-start gap-2">
                                <div
                                    class="mt-1 flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-gray-300 to-gray-400 shadow-sm dark:from-gray-600 dark:to-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M8.959 1.99l-.147 .028l-.115 .029a1 1 0 0 0 -.646 1.27l.749 2.245l-2.815 1.735a2 2 0 0 0 -.655 2.751l.089 .133a2 2 0 0 0 1.614 .819l1.563 -.001l-1.614 4.674a1 1 0 0 0 .945 1.327h7.961a1 1 0 0 0 1 -.978l.112 -5c0 -3.827 -1.555 -6.878 -4.67 -7.966l-2.399 -.83l-.375 -.121l-.258 -.074l-.135 -.031l-.101 -.013l-.055 -.001l-.048 .003z" />
                                        <path
                                            d="M18 18h-12a1 1 0 0 0 -1 1a2 2 0 0 0 2 2h10a2 2 0 0 0 1.987 -1.768l.011 -.174a1 1 0 0 0 -.998 -1.058z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="max-w-[85%] rounded-2xl border border-gray-200 bg-gray-100/95 p-3 shadow-sm dark:border-gray-700 dark:bg-gray-800/95">
                                        <div class="prose prose-sm max-w-none break-words text-sm text-gray-800 prose-headings:text-gray-900 prose-strong:text-gray-900 prose-code:text-gray-900 prose-a:text-primary-600 dark:text-gray-200 dark:prose-invert dark:prose-headings:text-white dark:prose-strong:text-white dark:prose-code:text-white dark:prose-a:text-primary-400">
                                            {!! Illuminate\Support\Str::of($message['content'])->markdown() !!}
                                        </div>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        {{ $message['timestamp'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach

            <!-- Typing Indicator -->
            @if ($isThinking)
                <div class="flex items-start gap-2">
                    <div class="mt-1 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-gray-300 dark:bg-gray-600"></div>
                    <div class="rounded-lg bg-gray-100 p-3 dark:bg-gray-800">
                        <div class="flex gap-1">
                            <div class="h-2 w-2 animate-bounce rounded-full bg-gray-400"></div>
                            <div class="h-2 w-2 animate-bounce rounded-full bg-gray-400" style="animation-delay: 0.1s"></div>
                            <div class="h-2 w-2 animate-bounce rounded-full bg-gray-400" style="animation-delay: 0.2s"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Message Input -->
        <form wire:submit="sendMessage" class="border-t border-gray-200 bg-white/80 p-3 backdrop-blur dark:border-gray-700 dark:bg-gray-900/70">
            <div class="flex items-center gap-2">
                <x-filament::input.wrapper class="flex-1">
                    <x-filament::input type="text" wire:model="message" x-ref="input"
                        placeholder="Type your message..." autocomplete="off" autocapitalize="off" autocorrect="off"
                        spellcheck="true" aria-label="Message" />
                </x-filament::input.wrapper>
                <x-filament::button type="submit" wire:loading.attr="disabled"
                    @class(['opacity-60 cursor-not-allowed' => blank($message ?? '')])>
                    <span wire:loading.remove>Send</span>
                    <span wire:loading wire:target="sendMessage">...</span>
                </x-filament::button>
            </div>
        </form>
    </div>

    <!-- Floating Toggle Button -->
    <button type="button"
        x-on:click="open = !open; if (open) { $nextTick(() => { $refs.input?.focus(); }); }"
        class="ml-auto flex h-14 w-14 items-center justify-center rounded-full bg-primary-500 text-white shadow-[0_10px_30px_-10px_rgba(59,130,246,0.6)] transition duration-200 hover:-translate-y-0.5 hover:bg-primary-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:ring-offset-gray-900"
        aria-label="Toggle chat assistant">
        <!-- Open Icon -->
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 56 56"
            fill="currentColor">
            <path d="M 26.6875 12.6602 C 26.9687 12.6602 27.1094 12.4961 27.1797 12.2383 C 27.9062 8.3242 27.8594 8.2305 31.9375 7.4570 C 32.2187 7.4102 32.3828 7.2461 32.3828 6.9648 C 32.3828 6.6836 32.2187 6.5195 31.9375 6.4726 C 27.8828 5.6524 28.0000 5.5586 27.1797 1.6914 C 27.1094 1.4336 26.9687 1.2695 26.6875 1.2695 C 26.4062 1.2695 26.2656 1.4336 26.1953 1.6914 C 25.3750 5.5586 25.5156 5.6524 21.4375 6.4726 C 21.1797 6.5195 20.9922 6.6836 20.9922 6.9648 C 20.9922 7.2461 21.1797 7.4102 21.4375 7.4570 C 25.5156 8.2774 25.4687 8.3242 26.1953 12.2383 C 26.2656 12.4961 26.4062 12.6602 26.6875 12.6602 Z M 15.3438 28.7852 C 15.7891 28.7852 16.0938 28.5039 16.1406 28.0821 C 16.9844 21.8242 17.1953 21.8242 23.6641 20.5821 C 24.0860 20.5117 24.3906 20.2305 24.3906 19.7852 C 24.3906 19.3633 24.0860 19.0586 23.6641 18.9883 C 17.1953 18.0977 16.9609 17.8867 16.1406 11.5117 C 16.0938 11.0899 15.7891 10.7852 15.3438 10.7852 C 14.9219 10.7852 14.6172 11.0899 14.5703 11.5352 C 13.7969 17.8164 13.4687 17.7930 7.0469 18.9883 C 6.6250 19.0821 6.3203 19.3633 6.3203 19.7852 C 6.3203 20.2539 6.6250 20.5117 7.1406 20.5821 C 13.5156 21.6133 13.7969 21.7774 14.5703 28.0352 C 14.6172 28.5039 14.9219 28.7852 15.3438 28.7852 Z M 31.2344 54.7305 C 31.8438 54.7305 32.2891 54.2852 32.4062 53.6524 C 34.0703 40.8086 35.8750 38.8633 48.5781 37.4570 C 49.2344 37.3867 49.6797 36.8945 49.6797 36.2852 C 49.6797 35.6758 49.2344 35.2070 48.5781 35.1133 C 35.8750 33.7070 34.0703 31.7617 32.4062 18.9180 C 32.2891 18.2852 31.8438 17.8633 31.2344 17.8633 C 30.6250 17.8633 30.1797 18.2852 30.0860 18.9180 C 28.4219 31.7617 26.5938 33.7070 13.9140 35.1133 C 13.2344 35.2070 12.7891 35.6758 12.7891 36.2852 C 12.7891 36.8945 13.2344 37.3867 13.9140 37.4570 C 26.5703 39.1211 28.3281 40.8321 30.0860 53.6524 C 30.1797 54.2852 30.6250 54.7305 31.2344 54.7305 Z" /></svg>
        <!-- Close Icon -->
        <svg x-cloak x-show="open" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 6l12 12" />
            <path d="M18 6L6 18" />
        </svg>
    </button>
</div>

@script
<script>
    $wire.on('scroll-to-bottom', () => {
        setTimeout(() => {
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }, 50);
    });
</script>
@endscript

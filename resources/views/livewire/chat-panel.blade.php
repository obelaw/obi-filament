<div>
    <div class="space-y-4">
        <!-- Chat Header -->
        <div class="flex items-center justify-between border-b pb-3 dark:border-gray-700">
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
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
            <x-filament::button color="gray" size="sm" wire:click="clearChat" icon="heroicon-o-trash">
                Clear
            </x-filament::button>
        </div>

        <!-- Messages Container -->
        <div class="h-96 max-h-96 overflow-y-auto rounded-lg border p-4 space-y-4 dark:border-gray-700"
            id="messages-container" role="log" aria-live="polite" aria-relevant="additions" aria-atomic="false"
            x-ref="container">
            @foreach ($messages as $message)
                <div class="mb-4 {{ $message['is_user'] ? 'flex justify-end' : '' }}" id="msg-{{ $message['id'] }}"
                    aria-label="{{ $message['is_user'] ? 'You' : 'Assistant' }} at {{ $message['timestamp'] }}">
                    @if ($message['is_user'])
                        <!-- User Message -->
                        <div class="max-w-xs lg:max-w-md">
                            <div class="rounded-lg bg-primary-500 p-3 text-white">
                                <p class="text-sm">{{ $message['content'] }}</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 text-right dark:text-gray-400">
                                {{ $message['timestamp'] }}
                            </p>
                        </div>
                    @else
                        <!-- Bot Message -->
                        <div class="max-w-xs lg:max-w-md">
                            <div class="flex items-start space-x-2 rtl:space-x-reverse">
                                <div class="w-6 h-6 bg-gray-300 dark:bg-gray-600 rounded-full flex-shrink-0 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M8.959 1.99l-.147 .028l-.115 .029a1 1 0 0 0 -.646 1.27l.749 2.245l-2.815 1.735a2 2 0 0 0 -.655 2.751l.089 .133a2 2 0 0 0 1.614 .819l1.563 -.001l-1.614 4.674a1 1 0 0 0 .945 1.327h7.961a1 1 0 0 0 1 -.978l.112 -5c0 -3.827 -1.555 -6.878 -4.67 -7.966l-2.399 -.83l-.375 -.121l-.258 -.074l-.135 -.031l-.101 -.013l-.055 -.001l-.048 .003z" />
                                        <path
                                            d="M18 18h-12a1 1 0 0 0 -1 1a2 2 0 0 0 2 2h10a2 2 0 0 0 1.987 -1.768l.011 -.174a1 1 0 0 0 -.998 -1.058z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="rounded-lg bg-gray-100 dark:bg-gray-800 p-3">
                                        <p class="text-sm text-gray-800 dark:text-gray-200">
                                            {{ $message['content'] }}
                                        </p>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1 dark:text-gray-400">
                                        {{ $message['timestamp'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach

            <!-- Typing Indicator -->
            @if (!empty($isThinking) && $isThinking)
                <div class="flex items-start space-x-2 rtl:space-x-reverse">
                    <div class="w-6 h-6 bg-gray-300 dark:bg-gray-600 rounded-full flex-shrink-0 mt-1"></div>
                    <div class="rounded-lg bg-gray-100 dark:bg-gray-800 p-3">
                        <div class="flex space-x-1 rtl:space-x-reverse">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s">
                            </div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s">
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div wire:loading wire:target="sendMessage" class="flex items-start space-x-2 rtl:space-x-reverse">
                    <div class="w-6 h-6 bg-gray-300 dark:bg-gray-600 rounded-full flex-shrink-0 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M8.959 1.99l-.147 .028l-.115 .029a1 1 0 0 0 -.646 1.27l.749 2.245l-2.815 1.735a2 2 0 0 0 -.655 2.751l.089 .133a2 2 0 0 0 1.614 .819l1.563 -.001l-1.614 4.674a1 1 0 0 0 .945 1.327h7.961a1 1 0 0 0 1 -.978l.112 -5c0 -3.827 -1.555 -6.878 -4.67 -7.966l-2.399 -.83l-.375 -.121l-.258 -.074l-.135 -.031l-.101 -.013l-.055 -.001l-.048 .003z" />
                            <path
                                d="M18 18h-12a1 1 0 0 0 -1 1a2 2 0 0 0 2 2h10a2 2 0 0 0 1.987 -1.768l.011 -.174a1 1 0 0 0 -.998 -1.058z" />
                        </svg>
                    </div>
                    <div class="rounded-lg bg-gray-100 dark:bg-gray-800 p-3">
                        <div class="flex space-x-1 rtl:space-x-reverse">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s">
                            </div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s">
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Message Input (avoid modal auto-close by not using a form submit) -->
        <div class="flex space-x-2 rtl:space-x-reverse"
            x-on:keydown.enter.prevent="Livewire.dispatch('fake-enter'); $wire.sendMessage();"
            aria-label="Send message">
            <x-filament::input.wrapper class="flex-1">
                <x-filament::input type="text" wire:model="message" placeholder="Type your message..."
                    autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="true" aria-label="Message"
                    x-ref="input" />
            </x-filament::input.wrapper>
            <x-filament::button type="button" wire:loading.attr="disabled" wire:click="sendMessage" @click.prevent.stop
                @class(['opacity-60 cursor-not-allowed' => blank($message ?? '')])>
                <span wire:loading.remove>Send</span>
            </x-filament::button>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('scroll-to-bottom', () => {
        // Use a short timeout to ensure Livewire has updated the DOM
        // before we try to scroll.
        setTimeout(() => {
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }, 50);
    });
</script>
@endscript
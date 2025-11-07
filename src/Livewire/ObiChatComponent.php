<?php

namespace Obelaw\Obi\Filament\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;
use Obelaw\Obi\Facades\Obi;
use Throwable;

class ObiChatComponent extends Component
{
    private const DEFAULT_GREETING = 'Hello, how can I help you today?';
    private const CLEAR_GREETING = 'Chat cleared. How can I help you?';

    /**
     * The current input message bound to the textarea/input.
     */
    public string $message = '';

    /**
     * Accumulated chat transcript.
     * Each item: [id: string, content: string, is_user: bool, timestamp: string]
     */
    public array $messages = [];

    /**
     * Indicates when the bot is generating a response (useful for spinners in the UI).
     */
    public bool $isThinking = false;

    /** @var array<string, string> */
    protected array $rules = [
        'message' => 'required|string|min:1|max:1000',
    ];

    public function mount(): void
    {
        $this->messages = [$this->makeMessage(self::DEFAULT_GREETING, false)];
    }

    public function sendMessage(): void
    {
        // Validate and normalize user input
        $this->validate(['message' => 'required|string|min:1|max:1000']);
        $normalized = trim($this->message);
        if ($normalized === '') return;

        // Add user message (sanitized)
        $this->addMessage($normalized, true, sanitize: true);

        $userMessage = $normalized;
        $this->reset('message');
        $this->dispatch('scroll-to-bottom');

        // Get bot response
        $this->isThinking = true;

        try {
            $this->getBotResponse($userMessage);
        } catch (Throwable $e) {
            // Gracefully inform the user and continue
            $this->addMessage('Sorry, I ran into an issue generating a response. Please try again.', false);
        } finally {
            $this->isThinking = false;
        }
    }

    private function getBotResponse(string $userMessage): void
    {
        $botResponse = Obi::prompt($userMessage);
        $this->addMessage($botResponse, false);
        $this->dispatch('scroll-to-bottom');
    }

    public function clearChat(): void
    {
        $this->messages = [$this->makeMessage(self::CLEAR_GREETING, false)];
    }

    public function render(): View
    {
        return view('obelaw-obi::livewire.chat-panel');
    }

    /**
     * Append a message to the transcript in a consistent shape.
     */
    private function addMessage(string $content, bool $isUser, bool $sanitize = false): void
    {
        $this->messages[] = $this->makeMessage($content, $isUser, $sanitize);
    }

    /**
     * Create a standardized message payload.
     *
     * @return array{id: string, content: string, is_user: bool, timestamp: string}
     */
    private function makeMessage(string $content, bool $isUser = false, bool $sanitize = false): array
    {
        $text = trim($content);
        if ($sanitize) {
            // Basic sanitation for user input; prefer escaping in view as well.
            $text = strip_tags($text);
        }

        return [
            'id' => Str::uuid()->toString(),
            'content' => $text,
            'is_user' => $isUser,
            'timestamp' => now()->format('H:i'),
        ];
    }
}

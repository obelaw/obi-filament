<?php

namespace Obelaw\Obi\Filament\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Throwable;

class ObiChatComponent extends Component
{
    /** @var mixed */
    protected $agent;

    private const DEFAULT_GREETING = 'Hello, how can I help you today?';
    private const CLEAR_GREETING = 'Chat cleared. How can I help you?';

    #[Locked]
    public ?string $resource = null;

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

    public function boot(): void
    {
        if ($this->resource) {
            $this->agent = $this->resource::ObiAgent();
        }
    }

    public function mount(?string $resource = null): void
    {
        $this->messages = [[
            'id' => (string) Str::uuid(),
            'content' => self::DEFAULT_GREETING,
            'is_user' => false,
            'timestamp' => now()->format('g:i A'),
        ]];
    }

    public function sendMessage(): void
    {
        $this->validate();

        $message = $this->message;
        $this->message = '';

        $this->messages[] = [
            'id' => (string) Str::uuid(),
            'content' => $message,
            'is_user' => true,
            'timestamp' => now()->format('g:i A'),
        ];

        $this->isThinking = true;

        try {
            $response = $this->resolveBotResponse($message);
        } catch (Throwable $e) {
            $response = 'Sorry, I ran into a problem. Please try again.';
        }

        $this->isThinking = false;

        $this->messages[] = [
            'id' => (string) Str::uuid(),
            'content' => $response,
            'is_user' => false,
            'timestamp' => now()->format('g:i A'),
        ];

        $this->dispatch('scroll-to-bottom');
    }

    public function resolveBotResponse(string $message): string
    {
        return (string) ($this->agent)($message);
    }

    public function clearChat(): void
    {
        $this->messages = [[
            'id' => (string) Str::uuid(),
            'content' => self::CLEAR_GREETING,
            'is_user' => false,
            'timestamp' => now()->format('g:i A'),
        ]];
    }

    public function render(): View
    {
        return view('obelaw-obi::livewire.chat-panel');
    }
}

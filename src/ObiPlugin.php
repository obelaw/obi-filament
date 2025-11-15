<?php

namespace Obelaw\Obi\Filament;

use Filament\Actions\Action;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class ObiPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }
    public function getId(): string
    {
        return 'obalaw-obi';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->userMenuItems([
                Action::make('obi-chat-assistant')
                    ->label(config('obi.agent.nickname', 'Obi') . ' Chat Assistant')
                    ->icon('heroicon-o-sparkles')
                    ->modalContent(
                        fn(): HtmlString => new HtmlString(
                            Blade::render('<livewire:obi-chat />')
                        )
                    )
                    ->slideOver(false)
                    ->modalWidth('lg')
                    ->modalHeading(config('obi.agent.nickname', 'Obi') . ' Chat Assistant')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
            ])
            ->viteTheme('vendor/obelaw/obi-filament/resources/css/filament/obi/theme.css');
    }

    public function boot(Panel $panel): void
    {
        //
    }
}

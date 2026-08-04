<?php

namespace Obelaw\Obi\Filament;

use Filament\Actions\Action;
use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Obelaw\Obi\Filament\Http\Middleware\SetCurrentFilamentResource;

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
            // ->userMenuItems([
            //     Action::make('obi-chat-assistant')
            //         ->label(config('obi.agent.nickname', 'Obi') . ' Chat Assistant')
            //         ->icon('heroicon-o-sparkles')
            //         ->modalContent(
            //             fn(): HtmlString => new HtmlString(
            //                 Blade::render('<livewire:obi-chat :agent="app()->get(\'obi.current_resource\')::obiAgent() ?? \'No resource found\'" />')
            //             )
            //         )
            //         ->slideOver(false)
            //         ->modalWidth('lg')
            //         ->modalHeading(config('obi.agent.nickname', 'Obi') . ' Chat Assistant')
            //         ->modalSubmitAction(false)
            //         ->modalCancelAction(false)
            // ])
            // ->renderHook(
            //     PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            //     // Render the Livewire component by its name
            //     fn(): string => Blade::render('<livewire:obi-chat :agent="app()->get(\'obi.current_resource\')::obiAgent() ?? \'No resource found\'" />'),
            // )
            ->middleware([
                SetCurrentFilamentResource::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}

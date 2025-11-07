<?php

namespace Obelaw\Obi\Filament;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Obelaw\Obi\Filament\Livewire\ObiChatComponent;

class ObiFilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Livewire::component('obi-chat', ObiChatComponent::class);

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'obelaw-obi');
    }
}

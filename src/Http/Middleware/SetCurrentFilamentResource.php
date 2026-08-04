<?php

namespace Obelaw\Obi\Filament\Http\Middleware;

use Closure;
use Filament\Resources\Pages\Page;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentFilamentResource
{
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();

        if ($route) {
            $controller = $route->getControllerClass();

            // Check if the current controller is a Filament Resource Page
            if ($controller && is_subclass_of($controller, Page::class) && method_exists($controller, 'getResource')) {
                $resourceClass = $controller::getResource();

                // Bind to the Laravel App Container globally for this request

                // \Obelaw\Obi\Filament\Obi::setCurrentResource($resourceClass);
                // app()->instance('obi.current_resource', $resourceClass);

                // dd($resourceClass::ObiAgent());

                if (is_subclass_of($resourceClass, \Obelaw\Obi\Filament\Contracts\HasObiAgent::class)) {
                    FilamentView::registerRenderHook(
                        PanelsRenderHook::PAGE_END,
                        fn(): string => Blade::render(
                            '<livewire:obi-chat :resource="$resource" />',
                            ['resource' => $resourceClass]
                        ),
                    );
                }
            }
        }

        return $next($request);
    }
}

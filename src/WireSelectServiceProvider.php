<?php

namespace Fyyyn1210\WireSelect;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class WireSelectServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register Livewire component
        Livewire::component('wire-select-box', Components\WireSelectBox::class);

        // Publish views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/wire-select'),
        ], 'views');


        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'wire-select');

        // Publish config
        $this->publishes([
            __DIR__.'/../config/wire-select.php' => config_path('wire-select.php'),
        ], 'config');

        // Publish assets (optional)
        $this->publishes([
            __DIR__.'/../resources/css' => public_path('vendor/wire-select/css'),
            __DIR__.'/../resources/js' => public_path('vendor/wire-select/js'),
        ], 'assets');
    }

    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__.'/../config/wire-select.php',
            'searchable-select'
        );
    }
}
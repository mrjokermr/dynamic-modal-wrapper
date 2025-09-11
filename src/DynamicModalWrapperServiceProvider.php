<?php
namespace Mrjokermr\DynamicModalWrapper;

use Illuminate\Support\ServiceProvider;
use Livewire;
use Mrjokermr\DynamicModalWrapper\Livewire\DynamicModalWrapper;

class DynamicModalWrapperServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/Resources/Views', 'dynamic-modal-wrapper');
        Livewire::component('dynamic-modal-wrapper', DynamicModalWrapper::class);

        $this->publishes([
            __DIR__ . '/Config/dynamic-modal-wrapper.php' => config_path('dynamic-modal-wrapper.php'),
        ], 'dynamic-modal-wrapper-config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/dynamic-modal-wrapper.php',
            'dynamic-modal-wrapper'
        );
    }
}

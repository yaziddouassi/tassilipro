<?php

namespace Tassili\Tassili;

use Illuminate\Support\ServiceProvider;


class TassiliServiceProvider extends ServiceProvider
{
   
    public function register(): void
    {
      $this->publishes([
            __DIR__.'/../config/tassili.php' => config_path('tassili.php'),
        ], 'tassili-config');

        $this->mergeConfigFrom(
            __DIR__.'/../config/tassili.php', 'tassili'
        );
    }

   
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->commands([
            \Tassili\Tassili\Commands\TassiliCreator::class,
            \Tassili\Tassili\Commands\PanelCreator::class,
            \Tassili\Tassili\Commands\CrudCommand::class,
            \Tassili\Tassili\Commands\WizardCommand::class,
            \Tassili\Tassili\Commands\CreateUser::class,
        ]);
        
    }
}
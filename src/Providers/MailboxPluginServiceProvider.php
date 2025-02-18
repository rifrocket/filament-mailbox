<?php

namespace RifRocket\FilamentMailbox\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use RifRocket\FilamentMailbox\MailboxPlugin;

class MailboxPluginServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot()
    {
        // Publish configuration file.
        $this->publishes([
            __DIR__ . '/../../config/mailbox.php' => config_path('mailbox.php'),
        ], 'filament-mailbox-config');

        // Publish translation files.
        $this->publishes([
            __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/filament-mailbox'),
        ], 'filament-mailbox-translations');

        // Load package translations with namespace.
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'filament-mailbox');

        // Load and publish migrations if running in console.
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        }

        // Load views for the plugin.
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'filament-mailbox');

        // Load JSON translations.
        $this->loadJsonTranslationsFrom(__DIR__ . '/../../resources/lang');

        // Load custom routes for the plugin (if any).
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
    }

    /**
     * Register any package services.
     */
    public function register()
    {
        // Merge plugin configuration.
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/mailbox.php', 'mailbox'
        );
    }
}


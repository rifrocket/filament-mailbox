<?php

namespace RifRocket\FilamentMailbox;

use Filament\Contracts\Plugin;
use RifRocket\FilamentMailbox\Filament\Pages\MailboxDashboard;
use RifRocket\FilamentMailbox\Filament\Resources\EmailResource;
use RifRocket\FilamentMailbox\Filament\Widgets\EmailStats;
use Filament\Panel;
use RifRocket\FilamentMailbox\Filament\Pages\Inbox;

class MailboxPlugin implements Plugin
{

    public function getId(): string
    {
        return 'filament-mailbox';
    }

        public static function make(): static
    {
        return app(static::class);
    }

    public function register(Panel $panel): void
    {
        $panel
        ->resources([
            EmailResource::class,
        ])
        ->pages([
            MailboxDashboard::class,
        ])
        ->widgets([
            EmailStats::class,
        ]);
    }
    public function boot(Panel $panel): void
    {
        // Perform any bootstrapping or initialization here.
    }

}

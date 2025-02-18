<?php

namespace RifRocket\FilamentMailbox\Filament\Pages;

use Filament\Pages\Page;
use RifRocket\FilamentMailbox\Models\Email;
use RifRocket\FilamentMailbox\Models\Folder;

class MailboxDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $title = 'Mailbox Dashboard';
    protected static string $view = 'filament-mailbox::filament.pages.mailbox-dashboard';
    protected static ?string $slug = 'mailbox-dashboard';

    // Public properties that can be accessed in the view.
    public int $totalEmails = 0;
    public int $unreadEmails = 0;
    public $folders = [];

    /**
     * The mount method is invoked when the component is instantiated.
     * It initializes the dashboard with current statistics.
     */
    public function mount(): void
    {
        $this->refreshStats();
    }

    /**
     * Refresh mailbox statistics.
     *
     * This method fetches the latest counts and data for display on the dashboard.
     * It can be triggered via Livewire polling or user interaction.
     *
     * @return void
     */
    public function refreshStats(): void
    {
        $this->totalEmails  = Email::count();
        $this->unreadEmails = Email::where('seen', false)->count();
        $this->folders      = Folder::all();
    }
}


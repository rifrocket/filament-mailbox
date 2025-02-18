<?php

namespace RifRocket\FilamentMailbox\Filament\Widgets;

use Filament\Widgets\Widget;
use RifRocket\FilamentMailbox\Models\Email;

class EmailStats extends Widget
{
    // Define the view used by this widget.
    protected static string $view = 'filament-mailbox::filament.widgets.email-stats';
    public $totalEmails = 0;
    public $unreadEmails = 0;

    // Optionally, enable Livewire polling to refresh the widget data.
    // This value can be a string like '30s' (30 seconds) or an integer in milliseconds.
    protected int|string $pollingInterval = '30s';

    /**
     * Prepare the data to be passed to the widget view.
     *
     * @return array
     */
    protected function getData(): array
    {
        $$this->totalEmails  = Email::count();
        $$this->unreadEmails = Email::where('seen', false)->count();

        return [
            'totalEmails'  => $totalEmails,
            'unreadEmails' => $unreadEmails,
        ];
    }
}


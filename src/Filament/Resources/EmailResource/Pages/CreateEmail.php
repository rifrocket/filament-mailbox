<?php

namespace RifRocket\FilamentMailbox\Filament\Resources\EmailResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use RifRocket\FilamentMailbox\Filament\Resources\EmailResource;
use RifRocket\FilamentMailbox\Mail\ComposeEmailMailable;
use Illuminate\Support\Facades\Mail;

class CreateEmail extends CreateRecord
{
    protected static string $resource = EmailResource::class;

    /**
     * Handle record creation and trigger email sending.
     *
     * Here we override the afterCreate hook so that after a new email record is
     * created via the form, we immediately send the email using the ComposeEmailMailable.
     *
     * @return void
     */
    protected function afterCreate(): void
    {
        // Access the created record from the form submission.
        $data = $this->record->toArray();

        // Send the email using the ComposeEmailMailable.
        Mail::send(new ComposeEmailMailable(
            $data['subject'] ?? 'No Subject',
            $data['body'] ?? '',
            $data['from'] ?? config('mail.from.address'),
            // Assuming 'to' is stored as an array or comma-separated string.
            is_array($data['to']) ? $data['to'] : explode(',', $data['to'])
        ));

        // Optionally, send a notification to the user.
        $this->notify('success', 'Email sent successfully.');
    }
}


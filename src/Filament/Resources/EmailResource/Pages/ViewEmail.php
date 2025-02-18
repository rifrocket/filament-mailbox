<?php

namespace RifRocket\FilamentMailbox\Filament\Resources\EmailResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use RifRocket\FilamentMailbox\Filament\Resources\EmailResource;
use RifRocket\FilamentMailbox\Models\Email;
use Filament\Actions\Action;
use Filament\Support\Enums\ActionSize;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use RifRocket\FilamentMailbox\Filament\Resources\Actions\NextAction;
use RifRocket\FilamentMailbox\Filament\Resources\Actions\PreviousAction;
use RifRocket\FilamentMailbox\Filament\Resources\Concerns\CanPaginateViewRecord;

class ViewEmail extends ViewRecord
{
    use CanPaginateViewRecord;

    public Email $email;

    public static function getResource(): string
    {
        return config('filament-mailbox.resource.class', EmailResource::class);
    }

    public function downloadAction(): Action
    {
        return Action::make('download')
            ->label(__('filament-mailbox::filament-mailbox.download'))
            ->requiresConfirmation()
            ->icon('heroicon-c-arrow-down-tray')
            ->size(ActionSize::ExtraSmall)
            ->action(function (array $arguments) {
                $fileExists = Storage::disk(config('filament-mailbox.attachments_disk'))->exists($arguments['path']);
                if ($fileExists) {
                    return Storage::disk(config('filament-mailbox.attachments_disk'))->download($arguments['path'], $arguments['name']);
                } else {
                    Notification::make()
                        ->title(__('filament-mailbox::filament-mailbox.download_attachment_error'))
                        ->danger()
                        ->duration(5000)
                        ->send();
                }
            });
    }

    protected function getHeaderActions(): array
    {
        return [
            PreviousAction::make(),
            NextAction::make(),
        ];
    }
}


<?php

namespace RifRocket\FilamentMailbox\Filament\Resources\EmailResource\Pages;

use RifRocket\FilamentMailbox\Filament\Resources\EmailResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Actions\CreateAction;
use RifRocket\FilamentMailbox\Models\Email;
use Filament\Actions\Concerns\InteractsWithActions;
use RifRocket\FilamentMailbox\Enums\EmailFolderEnums;

class ListEmails extends ListRecords
{
    use InteractsWithActions;

    public static function getResource(): string
    {
        return config('filament-mailbox.resource.class', EmailResource::class);
    }    
    
    protected static string $view = 'filament-mailbox::filament.pages.list-records';
 

    public function getTabs(): array
    {
        return [
            'All' => Tab::make()
                ->icon('heroicon-o-inbox-stack')
                ->query(fn($query) => $query->whereNotNull('folder_id'))
                ->badge(fn() => Email::whereNotNull('folder_id')->count()),
            EmailFolderEnums::INBOX->label() => Tab::make()
                ->icon(EmailFolderEnums::INBOX->icon())
                ->query(fn($query) => $query->where('folder_id', EmailFolderEnums::INBOX->value))
                ->badge(fn() => Email::where('folder_id', EmailFolderEnums::INBOX->value)->count()),
            EmailFolderEnums::SENT->label() => Tab::make()
                ->icon(EmailFolderEnums::SENT->icon())
                ->query(fn($query) => $query->where('folder_id', EmailFolderEnums::SENT->value))
                ->badge(fn() => Email::where('folder_id', EmailFolderEnums::SENT->value)->count()),
            EmailFolderEnums::DRAFTS->label() => Tab::make()
                ->icon(EmailFolderEnums::DRAFTS->icon())
                ->query(fn($query) => $query->where('folder_id', EmailFolderEnums::DRAFTS->value))
                ->badge(fn() => Email::where('folder_id', EmailFolderEnums::DRAFTS->value)->count()),
            EmailFolderEnums::TRASH->label() => Tab::make()
                ->icon(EmailFolderEnums::TRASH->icon())
                ->query(fn($query) => $query->where('folder_id', EmailFolderEnums::TRASH->value))
                ->badge(fn() => Email::where('folder_id', EmailFolderEnums::TRASH->value)->count()),
        ];
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make()->label('Compose')
            ->icon('heroicon-o-pencil')
            ,
        ];
    }
}

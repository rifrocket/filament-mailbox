<?php
namespace RifRocket\FilamentMailbox\Filament\Resources\Actions;

use Filament\Actions\Action;

class NextAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'next';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->hiddenLabel()
            ->icon('heroicon-o-arrow-right')
            ->outlined()
            ->size('sm')
            ->tooltip(__('filament-mailbox::filament-mailbox.next'));
    }
}
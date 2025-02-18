<?php

namespace RifRocket\FilamentMailbox\Filament\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use RifRocket\FilamentMailbox\Models\Email;
use RifRocket\FilamentMailbox\Filament\Resources\EmailResource\Pages;
use RifRocket\FilamentMailbox\Enums\FilamentMailboxEnums;
use Filament\Forms\Form;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\View;
use Filament\Forms\Components\ViewField;
use Illuminate\Contracts\Support\Htmlable;


class EmailResource extends Resource
{
    // Added property to enforce creation
    protected static bool $canCreate = true;

    public static function getModel(): string
    {
        return config('mailbox.resource.model') ?? Email::class;
    }

    public static function getSlug(): string
    {
        return config('mailbox.resource.slug') ?? 'mailbox';
    }   

    public static function getCluster(): ?string
    {
        return config('mailbox.resource.cluster');
    }

    public static function getBreadcrumb(): string
    {
        return  __('filament-mailbox::mailbox.resource.breadcrumb');
    }

    public static function getNavigationLabel(): string
    {
        return  __('filament-mailbox::mailbox.resource.navigation_label');
    }

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return config('mailbox.resource.navigation_icon') ?? 'heroicon-o-envelope';
    }

    public static function getNavigationGroup(): ?string
    {
        return config('mailbox.resource.navigation_group') ? __('filament-mailbox::mailbox.resource.navigation_group') : null;
    }

    public static function getNavigationSort(): ?int
    {
        return config('mailbox.resource.navigation_sort') ?? parent::getNavigationSort();
    }


    /**
     * Define the form schema for creating or editing emails.
     * In a mailbox plugin, this might be used for composing emails.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Envelope')
                    ->label('')
                    ->schema([
                        TextInput::make('from')
                            ->label(__('filament-mailbox::mailbox.form.from.label'))
                            ->columnSpan(2),
                        Textinput::make('to')
                            ->label(__('filament-mailbox::mailbox.form.to.label'))
                            ->columnSpan(2),
                            TagsInput::make('cc')
                            ->label(__('filament-mailbox::mailbox.form.cc.label'))
                            ->columnSpan(2),
                            TagsInput::make('bcc')
                            ->label(__('filament-mailbox::mailbox.form.bcc.label'))
                            ->columnSpan(2),
                        TextInput::make('subject')
                            ->label(__('filament-mailbox::mailbox.form.subject.label'))
                            ->columnSpan(3),
                        DateTimePicker::make('created_at')
                            ->format(config('filament-mailbox.resource.datetime_format'))
                            ->label(__('filament-mailbox::mailbox.form.created_at.label')),
                    ])->columns(4),
                Fieldset::make('attachments')
                    ->hidden(fn (): bool => ! config('filament-mailbox.store_attachments'))
                    ->label(__('filament-mailbox::mailbox.attachments'))
                    ->schema([
                        View::make('filament-mailbox::attachments')
                            ->columnSpanFull(),
                    ]),
                // Conditionally render the body field:
                request()->routeIs('filament.resources.email.create')
                    ? // Open form on create: show a plain Textarea
                      \Filament\Forms\Components\Textarea::make('body')
                          ->label(__('filament-mailbox::mailbox.form.body.label'))
                    : // ...existing code for tabbed layout...
                      Tabs::make('Content')->tabs([
                          Tabs\Tab::make(__('filament-mailbox::filament-mailbox.form.html'))
                              ->label(__('filament-mailbox::mailbox.form.body.label'))
                              ->schema([
                                  ViewField::make('body')
                                      ->label('')
                                      ->view('filament-mailbox::filament.pages.partials.html_view'),
                              ]),
                          Tabs\Tab::make('Email Chain')
                              ->schema([
                                  ViewField::make('email_chain')
                                      ->label('')
                                      ->view('filament-mailbox::filament.pages.partials.email-chain-timeline',['emailChain' => fn ($record) => $record->timeLine()]),
                              ]),
                      ])->columnSpan(2),
            ]);
    }

    /**
     * Define the table schema for listing emails.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->label('Subject')
                    ->sortable()
                    ->searchable()
                    ->limit(20) // limit the displayed subject length
                    ->tooltip(fn ($record) => $record->subject),
                Tables\Columns\TextColumn::make('from')
                    ->label('Sender')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_date')
                    ->label('Received')
                    ->dateTime('M d, Y')
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('seen')
                    ->label('Read'),
            ])
            ->filters([
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('date_from')->label('From'),
                        Forms\Components\DatePicker::make('date_until')->label('To'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['date_from'], fn($q, $date_from) => $q->whereDate('email_date', '>=', $date_from))
                            ->when($data['date_until'], fn($q, $date_until) => $q->whereDate('email_date', '<=', $date_until));
                    })
                    ->indicator(function (array $data): ?string {
                        $indicators = [];
                        if (!empty($data['date_from'])) {
                            $indicators[] = 'From: '.$data['date_from'];
                        }
                        if (!empty($data['date_until'])) {
                            $indicators[] = 'To: '.$data['date_until'];
                        }
                        return count($indicators) ? implode(', ', $indicators) : null;
                    }),
                Tables\Filters\Filter::make('seen_status')
                    ->form([
                        Forms\Components\Select::make('seen')
                            ->options([
                                1 => 'Read',
                                0 => 'Unread',
                            ])
                            ->label('Seen Status'),
                    ])
                    ->query(function ($query, array $data) {
                        return isset($data['seen'])
                            ? $query->where('seen', $data['seen'])
                            : $query;
                    })
                    ->indicator(function (array $data): ?string {
                        return isset($data['seen']) ? ($data['seen'] ? 'Read' : 'Unread') : null;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make(FilamentMailboxEnums::MARK_AS_SEEN->value)
                    ->iconButton()
                    ->icon(FilamentMailboxEnums::MARK_AS_SEEN->icon())
                    ->tooltip(FilamentMailboxEnums::MARK_AS_SEEN->label())
                    ->action(function (Email $record) {
                        $record->update(['seen' => true]);
                    })
                    ->requiresConfirmation()
                    ->visible(fn (Email $record): bool => !$record->seen)
                    ->color(FilamentMailboxEnums::MARK_AS_SEEN->color()),
                Tables\Actions\Action::make(FilamentMailboxEnums::MARK_AS_UNREAD->value)
                    ->iconButton()
                    ->icon(FilamentMailboxEnums::MARK_AS_UNREAD->icon())
                    ->tooltip(FilamentMailboxEnums::MARK_AS_UNREAD->label())
                    ->action(function (Email $record) {
                        $record->update(['seen' => false]);
                    })
                    ->requiresConfirmation()
                    ->visible(fn (Email $record): bool => $record->seen)
                    ->color(FilamentMailboxEnums::MARK_AS_UNREAD->color()),
                Tables\Actions\Action::make(FilamentMailboxEnums::FORWARD->value)
                    ->iconButton()
                    ->icon(FilamentMailboxEnums::FORWARD->icon())
                    ->tooltip(FilamentMailboxEnums::FORWARD->label())
                    ->action(function (Email $record) {
                        // ...forward email logic...
                    })
                    ->color(FilamentMailboxEnums::FORWARD->color()),
                Tables\Actions\Action::make(FilamentMailboxEnums::REPLY->value)
                    ->iconButton()
                    ->icon(FilamentMailboxEnums::REPLY->icon())
                    ->tooltip(FilamentMailboxEnums::REPLY->label())
                    ->action(function (Email $record) {
                        // ...reply email logic...
                    })
                    ->color(FilamentMailboxEnums::REPLY->color()),
                Tables\Actions\DeleteAction::make('delete')
                    ->iconButton()
                    ->tooltip('Delete Email'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /**
     * Define any relation managers (e.g., attachments) associated with this resource.
     */
    public static function getRelations(): array
    {
        return [
            // Example: AttachmentsRelationManager::class,
        ];
    }

    /**
     * Define the pages available for this resource.
     */
    public static function getPages(): array
    {
        return [
            'index'   => Pages\ListEmails::route('/'),
            'view'    => Pages\ViewEmail::route('/{record}'),
            'create'  => Pages\CreateEmail::route('/create'),
        ];
    }

    // Added canCreate method to enable the create button.
    // public static function canCreate(): bool
    // {
    //     return true;
    // }

    // public static function canAccess(): bool
    // {
    //     $roles = config('filament-mailbox.can_access.role', []);

    //     if (method_exists(filament()->auth()->user(), 'hasRole') && ! empty($roles)) {
    //         return filament()->auth()->user()->hasRole($roles);
    //     }

    //     return true;
    // }
}


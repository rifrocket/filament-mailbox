<?php

namespace RifRocket\FilamentMailbox\Filament\Resources\EmailResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class AttachmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'attachments';

    protected static ?string $recordTitleAttribute = 'filename';

    /**
     * Define the form schema for creating/editing an attachment.
     */
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            // FileUpload component for handling attachment uploads.
            Forms\Components\FileUpload::make('file')
                ->label('Attachment')
                ->disk('public') // Adjust disk as needed.
                ->directory('attachments')
                ->required(),
            Forms\Components\TextInput::make('filename')
                ->label('File Name')
                ->required(),
            // Optionally include additional fields such as file size or MIME type.
        ]);
    }

    /**
     * Define the table schema for listing attachments.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Display the file name.
                Tables\Columns\TextColumn::make('filename')
                    ->label('File Name')
                    ->searchable(),
                // Display the file size, formatting bytes into kilobytes.
                Tables\Columns\TextColumn::make('size')
                    ->label('Size')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state / 1024, 2) . ' KB' : '-'),
                // Show the upload date.
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Uploaded')
                    ->dateTime('M d, Y'),
            ])
            ->filters([
                // Add any filters if needed.
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}


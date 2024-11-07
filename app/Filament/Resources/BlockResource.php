<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlockResource\Pages;
use App\Filament\Resources\BlockResource\RelationManagers;
use App\Models\Block;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlockResource extends Resource
{
    protected static ?string $model = Block::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return __('Blocks');
    }
    public static function form(Form $form): Form
    {
        return $form->columns(1)
            ->schema([
                Select::make('category_id')->relationship('category', 'name', fn(Builder $query) => $query->where('parent_id', '<>', 'null')),
                Repeater::make('slides')
                    ->label('Slides')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required(),
                        Textarea::make('description')
                            ->label('Description')
                            ->nullable(),
                        FileUpload::make('image_path')->image()->imageEditor()->imagePreviewHeight('100%')->directory('slides')->columnSpan('full')->required(),
                        TextInput::make('link')
                            ->label('Link')
                            ->nullable(),
                    ])->addable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlocks::route('/'),
            'create' => Pages\CreateBlock::route('/create'),
            'edit' => Pages\EditBlock::route('/{record}/edit'),
        ];
    }
}

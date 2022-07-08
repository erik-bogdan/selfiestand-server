<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

        
    public static function getNavigationLabel(): string
    {
        return __('project.menuTitle');
    }

    public static function getLabel(): string
    {
        return __('project.title');
    }

    public static function getPluralLabel(): string
    {
        return __('project.pluralTitle');
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('project_title')
                    ->required()
                    ->maxLength(200),
                Forms\Components\DateTimePicker::make('project_date')
                    ->required(),
                Forms\Components\FileUpload::make('frame_image')
                    ->disk('public')
                    ->directory('frames')
                    ->enableDownload()
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project_title')->label(__('project.input.project_title')),
                Tables\Columns\TextColumn::make('uuid')->label(__('project.input.uuid')),
                Tables\Columns\TextColumn::make('project_date')->label(__('project.input.project_date'))
                    ->dateTime('Y-m-d H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(__('Szerkesztés')),
                Action::make(__('project.gallery.title'))
                    ->url(fn (Project $record): string => route('filament.resources.projects.gallery', $record))
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
            'gallery' => Pages\GalleryProject::route('/{record}/gallery'),
        ];
    }
}

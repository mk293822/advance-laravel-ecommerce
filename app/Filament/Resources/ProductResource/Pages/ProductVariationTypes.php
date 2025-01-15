<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Enums\ProductVariationTypesEnum;
use App\Filament\Resources\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;

class ProductVariationTypes extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected static ?string $navigationIcon = 'heroicon-m-numbered-list';

    protected static ?string $title = 'Variation Types';
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Repeater::make("variationTypes")
                ->label(false)
                ->relationship()
                ->collapsible()
                ->defaultItems(1)
                ->addActionLabel('Add New Variation Type')
                ->columns(2)
                ->columnSpan(2)
                ->schema([
                    TextInput::make('name')->required(),
                    Select::make('type')->options(ProductVariationTypesEnum::labels())->required(),
                    Repeater::make('options')
                        ->relationship()
                        ->collapsible()
                        ->schema([
                            TextInput::make('name')->columnSpan(2)->required(),
                            SpatieMediaLibraryFileUpload::make('images')
                                ->image()
                                ->multiple()
                                ->openable()
                                ->panelLayout('grid')
                                ->collection('images')
                                ->reorderable()
                                ->appendFiles()
                                ->preserveFilenames()
                                ->columnSpan(3)
                        ])
                        ->columnSpan(2)
                ])
        ]);
    }

}

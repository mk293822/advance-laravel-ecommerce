<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Enums\ProductVariationTypesEnum;
use App\Filament\Resources\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Collection;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use function Pest\Laravel\options;

class ProductVariations extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $title = 'Variation';
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        // dd($this);
        $types = $this->record->variationTypes;
        $fields = [];

        foreach ($types as $type) {
            $fields[] = TextInput::make('variation_type_' . $type->id . '.id')->hidden();
            $fields[] = TextInput::make('variation_type_' . $type->id . '.name')->label($type->name);
        }
        // dd($types);
        return $form->schema([
            Repeater::make('variations')
                ->label(false)
                ->collapsible()
                ->addable(false)
                ->defaultItems(1)
                ->schema([
                    Section::make()
                        ->schema($fields)
                        ->columns(3),
                    TextInput::make('quantity')
                        ->label('Quantity')
                        ->numeric(),
                    TextInput::make('price')
                        ->label('Price')
                        ->numeric(),
                ])
                ->columns(2)
                ->columnSpan(2)
        ]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {

        $variations = $this->record->variations->toArray();

        // dd($variations);
        $data['variations'] = $this->mergeCartesianWithExisting($this->record->variationTypes, $variations);

        // dd($data);
        return $data;
    }

    private function mergeCartesianWithExisting($variationTypes, $existingData): array
    {
        $defaultQuantity = $this->record->quantity;
        $defaultPrice = $this->record->price;
        $cartesianProduct = $this->cartesianProduct($variationTypes, $defaultQuantity, $defaultPrice);
        $mergeResult = [];

        foreach ($cartesianProduct as $product) {
            // Debugging line to check the structure of $product
            // dd($product);

            $optionIds = collect($product)
                ->filter(fn($_, $key) => str_starts_with($key, 'variation_type_'))
                ->map(fn($option) => $option['id'])
                ->values()
                ->toArray();

            $match = array_filter($existingData, function ($existingOptions) use ($optionIds) {
                return $existingOptions["variation_type_option_ids"] === $optionIds;
            });

            if (!empty($match)) {
                $existingEntry = reset($match);
                $product['id'] = $existingEntry['id'];
                $product['quantity'] = $existingEntry['quantity'];
                $product['price'] = $existingEntry['price'];
            } else {
                $product['quantity'] = $defaultQuantity;
                $product['price'] = $defaultPrice;
            }

            $mergeResult[] = $product;
        }
        //        dd($mergeResult);
        return $mergeResult;
    }

    private function cartesianProduct($variationTypes, $defaultQuantity = null, $defaultPrice = null): array
    {
        $result = [[]];

        foreach ($variationTypes as $variationType) {
            $temp = [];

            foreach ($variationType->options as $option) {

                foreach ($result as $combination) {
                    $newCombination = $combination + [
                        'variation_type_' . ($variationType->id) => [
                            'id' => $option->id,
                            'name' => $option->name,
                            'label' => $variationType->name
                        ],
                    ];

                    $temp[] = $newCombination;
                }
            }
            $result = $temp;
        }
        foreach ($result as $key => &$combination) {
            if (count($combination) === count($variationTypes)) {
                $combination['quantity'] = $defaultQuantity;
                $combination['price'] = $defaultPrice;
                $combination['id'] = $key;
            }
        }
        //         dd($result);
        return $result;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $formattedData = [];

        // dd($data);

        foreach ($data['variations'] as $option) {
            $variationTypeOptionIds = [];
            foreach ($this->record->variationTypes as $variationType) {
                $variationTypeOptionIds[] = $option['variation_type_' . $variationType->id]['id'] ?? null;
            }

            // dd($option);
            $quantity = $option['quantity'];
            $price = $option['price'];

            $formattedData[] = [
                'id' => $option['id'],
                'variation_type_option_ids' => $variationTypeOptionIds,
                'quantity' => $quantity,
                'price' => $price,
            ];
        }

        $data['variations'] = $formattedData;
        //         dd($data);
        return $data;
    }



    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $variations = $data['variations'];
        // dd($data, $record);
        //      dd($variations);
        unset($data['variations']);

        //         dd($variations);
        $variations = collect($variations)->map(function ($variation) {
            return [
                'id' => $variation['id'],
                'variation_type_option_ids' => json_encode($variation['variation_type_option_ids']),
                'price' => $variation['price'],
                'quantity' => $variation['quantity'],
            ];
        })->toArray();

        $record->variations()->upsert($variations, ['id'], ['variation_type_option_ids', 'price', 'quantity']);

        //         dd($record);
        return $record;
    }
}

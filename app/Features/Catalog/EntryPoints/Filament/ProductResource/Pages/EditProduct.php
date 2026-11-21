<?php

declare(strict_types=1);

namespace App\Features\Catalog\EntryPoints\Filament\ProductResource\Pages;

use AIArmada\Products\Actions\UpdateProduct as UpdateProductAction;
use AIArmada\Products\Models\Product;
use App\Features\Catalog\Actions\SaveMediaProfile;
use App\Features\Catalog\EntryPoints\Filament\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $profile = $this->getRecord()->mediaProfile;

        return $profile === null ? $data : [...$data, ...Arr::only($profile->attributesToArray(), ProductResource::mediaFields())];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data): Product {
            $product = app(UpdateProductAction::class)->execute(
                $record,
                Arr::except($data, ProductResource::mediaFields()),
            );

            app(SaveMediaProfile::class)->handle(
                $product->id,
                Arr::only($data, ProductResource::mediaFields()),
            );

            return $product;
        });
    }
}

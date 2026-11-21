<?php

declare(strict_types=1);

namespace App\Features\Catalog\EntryPoints\Filament\ProductResource\Pages;

use AIArmada\Products\Actions\CreateProduct as CreateProductAction;
use AIArmada\Products\Models\Product;
use App\Features\Catalog\Actions\SaveMediaProfile;
use App\Features\Catalog\EntryPoints\Filament\ProductResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data): Product {
            $product = app(CreateProductAction::class)->execute(
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

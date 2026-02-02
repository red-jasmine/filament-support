<?php

namespace RedJasmine\FilamentSupport\Resources\JsonSchemaResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentSupport\Resources\JsonSchemaResource;

class ListJsonSchemas extends ListRecords
{
    protected static string $resource = JsonSchemaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}


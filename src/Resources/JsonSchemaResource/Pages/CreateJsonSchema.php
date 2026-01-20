<?php

namespace RedJasmine\FilamentSupport\Resources\JsonSchemaResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentSupport\Resources\JsonSchemaResource;

class CreateJsonSchema extends CreateRecord
{
    protected static string $resource = JsonSchemaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 确保 schema 是数组
        if (empty($data['schema']) || !is_array($data['schema'])) {
            $data['schema'] = [
                '$schema' => 'http://json-schema.org/draft-07/schema#',
                'type' => 'object',
                'properties' => [],
            ];
        }

        return $data;
    }
}


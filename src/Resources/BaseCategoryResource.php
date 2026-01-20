<?php

namespace RedJasmine\FilamentSupport\Resources;

use Filament\Schemas\Schema;
use Filament\Tables\Table;
use RedJasmine\FilamentSupport\Resources\Schemas\CategoryForm;
use RedJasmine\FilamentSupport\Resources\Tables\CategoryTable;
use RedJasmine\Support\Foundation\Facades\Hook;

/**
 * @property bool $isTranslatable
 * @property bool $onlyOwner
 * @property-read  string $hookName
 */
trait BaseCategoryResource
{


    public static function form(Schema $schema) : Schema
    {
        if (method_exists(static::class, 'getHookName')) {
            return Hook::hook(static::getHookName('form'), $schema, function ($schema) {
                return static::categoryForm($schema);
            });
        } else {
            return static::categoryForm($schema);
        }

    }


    public static function categoryForm(Schema $schema) : Schema
    {
        return CategoryForm::configure($schema, static::$onlyOwner ?? false, static::$isTranslatable ?? false);
    }

    public static function table(Table $table) : Table
    {

        if (method_exists(static::class, 'getHookName')) {
            return Hook::hook(static::getHookName('table'), $table, function ($table) {
                return CategoryTable::configure($table);
            });
        } else {
            return CategoryTable::configure($table);
        }

    }


}
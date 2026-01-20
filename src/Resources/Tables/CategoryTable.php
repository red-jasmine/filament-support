<?php

namespace RedJasmine\FilamentSupport\Resources\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use RedJasmine\FilamentSupport\Filters\TreeParent;
use RedJasmine\Support\Domain\Models\Enums\UniversalStatusEnum;

class CategoryTable
{

    public static function configure(Table $table) : Table
    {

        $table
            ->columns([

                TextColumn::make('id')
                          ->label(__('red-jasmine-support::category.fields.id'))
                          ->copyable(),
                TextColumn::make('parent.name')
                          ->label(__('red-jasmine-support::category.fields.parent_id'))
                          ->sortable(),
                TextColumn::make('name')
                          ->label(__('red-jasmine-support::category.fields.name'))
                          ->searchable()->copyable(),
                ImageColumn::make('imageUrl')
                           ->label(__('red-jasmine-support::category.fields.image'))
                ,
                TextColumn::make('cluster')
                          ->label(__('red-jasmine-support::category.fields.cluster'))
                          ->searchable(),
                IconColumn::make('is_leaf')
                          ->label(__('red-jasmine-support::category.fields.is_leaf'))
                          ->boolean()
                          ->toggleable(isToggledHiddenByDefault: true)
                ,

                TextColumn::make('sort')
                          ->label(__('red-jasmine-support::category.fields.sort'))
                          ->sortable(),
                TextColumn::make('status')
                          ->label(__('red-jasmine-support::category.fields.status'))
                          ->useEnum(),
                TextColumn::make('visibility')
                          ->label(__('red-jasmine-support::category.fields.visibility'))
                          ->useEnum(),


            ])
            ->filters([
                TreeParent::make('tree')->label(__('red-jasmine-support::category.fields.parent_id')),
                SelectFilter::make('status')
                            ->label(__('red-jasmine-support::category.fields.status'))
                            ->options(UniversalStatusEnum::options()),
                TernaryFilter::make('visibility')
                             ->label(__('red-jasmine-support::category.fields.visibility'))
                ,

            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);

        OperatorTable::configure($table);
        return $table;
    }
}
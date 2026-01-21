<?php

namespace RedJasmine\FilamentSupport\Resources\Schemas;


use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\FilamentSupport\Forms\Components\SelectTree;
use RedJasmine\FilamentSupport\Forms\Components\TranslatableTabs;
use RedJasmine\Support\Domain\Models\Enums\UniversalStatusEnum;
use RedJasmine\Support\Domain\Models\Enums\VisibilityEnum;

class CategoryForm
{

    public bool $hasOwner = false;
    public bool $hasImage = false;

    public function configure(Schema $schema) : Schema
    {
        $owner = $this->hasOwner ? [Owner::make()] : [];
        $schema->components([
            Flex::make([
                Section::make([
                    ...$owner,
                    SelectTree::make('parent_id')
                              ->withTranslation()
                              ->label(__('red-jasmine-support::category.fields.parent_id'))
                              ->relationship(
                                  relationship: 'parent',
                                  titleAttribute: 'name',
                                  parentAttribute: 'parent_id',
                                  modifyQueryUsing: fn($query, Get $get, ?Model $record) => $query
                                      ->when($this->hasOwner, fn($query, $value) => $query->where('owner_type',
                                          $get('owner_type'))
                                                                                          ->where('owner_id',
                                                                                              $get('owner_id')))
                                      ->when($record?->getKey(),
                                          fn($query, $value) => $query->where('id', '<>', $value)),
                                  modifyChildQueryUsing: fn($query, Get $get, ?Model $record) => $query
                                      ->when($this->hasOwner, fn($query, $value) => $query->where('owner_type',
                                          $get('owner_type'))
                                                                                          ->where('owner_id',
                                                                                              $get('owner_id')))
                                      ->when($record?->getKey(),
                                          fn($query, $value) => $query->where('id', '<>', $value)),
                              )
                              ->searchable()
                              ->default(0)
                              ->enableBranchNode()
                              ->parentNullValue(0)
                              ->dehydrateStateUsing(fn($state) => (int) $state),

                    Section::make()->id('contentFields')
                           ->schema([
                               TextInput::make('name')
                                        ->label(__('red-jasmine-support::category.fields.name'))
                                        ->required()
                                        ->maxLength(255),
                               TextInput::make('cluster')
                                        ->label(__('red-jasmine-support::category.fields.cluster'))
                                        ->maxLength(255),

                               RichEditor::make('description')
                                         ->label(__('red-jasmine-support::category.fields.description')),
                           ]),


                    KeyValue::make('extra')
                            ->default([])
                            ->label(__('red-jasmine-support::category.fields.extra'))


                ]),

                Section::make([
                    $this->getImageField(),
                    TextInput::make('icon')
                             ->label(__('red-jasmine-support::category.fields.icon'))
                    ,
                    ColorPicker::make('color')
                               ->label(__('red-jasmine-support::category.fields.color'))
                    ,
                    TextInput::make('slug')
                             ->label(__('red-jasmine-support::category.fields.slug'))
                             ->maxLength(255),
                    TextInput::make('sort')
                             ->label(__('red-jasmine-support::category.fields.sort'))
                             ->required()
                             ->default(0),


                    ToggleButtons::make('is_leaf')
                                 ->label(__('red-jasmine-support::category.fields.is_leaf'))
                                 ->required()
                                 ->boolean()
                                 ->inline()
                                 ->inlineLabel()
                                 ->default(false),
                    ToggleButtons::make('status')
                                 ->label(__('red-jasmine-support::category.fields.status'))
                                 ->required()
                                 ->inline()
                                 ->default(UniversalStatusEnum::ENABLE)
                                 ->useEnum(UniversalStatusEnum::class),

                    Radio::make('visibility')
                         ->label(__('red-jasmine-support::category.fields.visibility'))
                         ->required()
                         ->inline()
                         ->default(VisibilityEnum::VISIBLE)
                         ->useEnum(VisibilityEnum::class),

                ])->grow(false),

            ])->columnSpanFull(),


        ]);


        return $schema;
    }

    protected function getImageField() : ?SpatieMediaLibraryFileUpload
    {
        if ($this->hasImage) {
            return SpatieMediaLibraryFileUpload::make('image')
                                               ->label(__('red-jasmine-support::category.fields.image'))
                                               ->saveRelationshipsUsing(null)
                                               ->saveUploadedFileUsing(null)
                                               ->dehydrated()
                                               ->collection('image')
                                               ->dehydrateStateUsing(function ($state) {
                                                   if (is_string($state)) {
                                                       return $state;
                                                   }
                                                   if (is_object($state)) {
                                                       return $state->getRealPath();
                                                   }
                                               });
        }
        return null;
    }
}

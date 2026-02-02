<?php

namespace RedJasmine\FilamentSupport\Resources\Schemas;

use Closure;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Facades\Auth;
use RedJasmine\Support\Domain\Contracts\BelongsToOwnerInterface;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\UserData;

class Owner extends FusedGroup
{


    protected string $ownerKey = 'owner';

    protected function getOwner() : UserInterface
    {
        $user  = Auth::user();
        $owner = $user instanceof BelongsToOwnerInterface ? $user->owner() : $user;
        if (($owner instanceof UserInterface) === false) {
            $owner = UserData::from([
                'type' => $owner::class,
                'id'   => $owner->getKey(),
            ]);
        }
        return $owner;

    }

    protected function setUp() : void
    {

        parent::setUp();
        $owner = $this->getOwner();
        $this->schema([
            Hidden::make($this->ownerKey)->default([
                'type' => $owner->getType(),
                'id'   => $owner->getID(),
            ])
            ,
            TextInput::make($this->ownerKey.'_type')
                //->prefix(__('red-jasmine-support::support.owner_type'))
                     ->label(__('red-jasmine-support::support.owner_type'))
                     ->default($owner->getType())
                     ->required()
                     ->maxLength(64)
                     ->live()
                     ->afterStateUpdated(function (Component $component, Set $set, Get $get) {
                         $set($this->ownerKey, [
                             'type' => $get($this->ownerKey.'_type'),
                             'id'   => $get($this->ownerKey.'_id')
                         ]);
                     }),

            TextInput::make($this->ownerKey.'_id')
                     ->prefix('ID')
                     ->label(__('red-jasmine-support::support.owner_id'))
                     ->required()
                     ->default($owner->getID())
                     ->live()
                     ->afterStateUpdated(function (Component $component, Set $set, Get $get) {
                         $set($this->ownerKey, [
                             'type' => $get($this->ownerKey.'_type'),
                             'id'   => $get($this->ownerKey.'_id')
                         ]);
                     }),

        ]);


        $this->label(__('red-jasmine-support::support.owner'));
        $this->columns(2);
    }

    public static function make(array|Closure $schema = [], string $ownerKey = 'owner', $disabled = false) : static
    {
        $static           = app(static::class, ['schema' => $schema,]);
        $static->ownerKey = $ownerKey;
        $static->configure();
        return $static;

    }
}
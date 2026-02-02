<?php

namespace RedJasmine\FilamentSupport\Helpers;

use Filament\Facades\Filament;
use RedJasmine\Support\Domain\Contracts\SystemAdmin;

trait HasSystemPermission
{
    public static function canAccess() : bool
    {
        $user = Filament::auth()->user();
        return ($user instanceof SystemAdmin)  && parent::canAccess();
    }

}
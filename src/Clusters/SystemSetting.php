<?php

namespace RedJasmine\FilamentSupport\Clusters;

use Filament\Clusters\Cluster;

class SystemSetting extends Cluster
{

    protected static ?int $navigationSort = 99999;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-8-tooth';

    public static function getNavigationLabel(): string
    {
        return  '系统设置';
        return __('red-jasmine-filament-ecommerce::settings.label');
    }

    public static function getClusterBreadcrumb(): ?string
    {
        return  '系统设置';
        return __('red-jasmine-filament-ecommerce::settings.label');
    }
}
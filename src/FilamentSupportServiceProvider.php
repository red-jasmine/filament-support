<?php

namespace RedJasmine\FilamentSupport;

use Filament\Actions\Exports\ExportColumn;
use Filament\Forms\Components\Field;
use Filament\Infolists\Components\Entry;
use Filament\Support\Assets\Asset;
use Filament\Support\Components\ViewComponent;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use RedJasmine\FilamentSupport\Livewire\MoneySynth;
use RedJasmine\FilamentSupport\Testing\TestsFilamentSupport;
use ReflectionClass;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentSupportServiceProvider extends PackageServiceProvider
{
    public static string $name = 'red-jasmine-filament-support';

    public static string $viewNamespace = 'red-jasmine-filament-support';

    public function configurePackage(Package $package) : void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
                ->hasCommands($this->getCommands())
                ->runsMigrations()
                ->hasInstallCommand(function (InstallCommand $command) {
                    $command
                        ->publishConfigFile()
                        ->publishMigrations()
                        ->askToRunMigrations()
                        ->askToStarRepoOnGitHub('red-jasmine/filament-support');
                });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }


        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands() : array
    {
        return [

        ];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations() : array
    {
        return [
        ];
    }

    public function packageRegistered() : void
    {
    }

    public function packageBooted() : void
    {

        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        BaseFilter::macro('isSetLabel', function () {
            return $this->label !== null;
        });
        ViewComponent::macro('isSetLabel', function () {
            return $this->label !== null;
        });
        Column::macro('isSetLabel', function () {
            return $this->label !== null;
        });
        Column::macro('useEnum', function () {

            if (method_exists($this, 'badge')) {
                $this->badge();
            }
            if (method_exists($this, 'formatStateUsing')) {
                $this->formatStateUsing(fn($state) => $state->getLabel());
            }
            if (method_exists($this, 'color')) {
                $this->color(fn($state) => $state->getColor());
            }
            if (method_exists($this, 'icon')) {
                $this->icon(fn($state) => $state->getIcon());
            }
            return $this;

        });
        ExportColumn::macro('useEnum', function () {
            if (method_exists($this, 'formatStateUsing')) {
                $this->formatStateUsing(fn($state) : string => $state->getLabel());
            }
            return $this;
        });


        Entry::macro('useEnum', function () {
            if (method_exists($this, 'badge')) {
                $this->badge();
            }
            if (method_exists($this, 'formatStateUsing')) {
                $this->formatStateUsing(fn($state) => $state->getLabel());
            }
            if (method_exists($this, 'color')) {
                $this->color(fn($state) => $state->getColor());
            }
            if (method_exists($this, 'icon')) {
                $this->icon(fn($state) => $state->getIcon());
            }
            return $this;

        });
        Field::macro('defaultZero', function () {
            $this->default(0)
                 ->formatStateUsing(fn($state) => $state === 0 ? null : $state)
                 ->mutateDehydratedStateUsing(fn($state) => $state ?? 0);
            return $this;
        });

        Field::macro('useEnum', function (string $enumClassName) {

            if(class_exists($enumClassName)){
                $reflection = new ReflectionClass($enumClassName);
                if ($reflection->isEnum()) {
                    $this->enum($enumClassName);
                    if (method_exists($this, 'options')) {
                        $this->options($enumClassName::options());
                    }
                    if (method_exists($this, 'colors')) {
                        $this->colors($enumClassName::colors());
                    }
                    if (method_exists($this, 'icons')) {
                        $this->icons($enumClassName::icons());
                    }
                }else{
                    $service =  app($enumClassName);
                    if (method_exists($this, 'options')) {
                        $this->options($service->options());
                    }
                    if (method_exists($this, 'colors')) {
                        $this->colors($service->colors());
                    }
                    if (method_exists($this, 'icons')) {
                        $this->icons($service->icons());
                    }
                }
            }


            return $this;
        });



        //Table::$defaultDateTimeDisplayFormat = 'Y-m-d H:i:s';


    }

    /**
     * @return array<Asset>
     */
    protected function getAssets() : array
    {
        return [
            // AlpineComponent::make('filament-support', __DIR__ . '/../resources/dist/components/filament-support.js'),
            //Css::make('filament-support-styles', __DIR__ . '/../resources/dist/filament-support.css'),
            //Js::make('filament-support-scripts', __DIR__ . '/../resources/dist/filament-support.js'),
        ];
    }

    protected function getAssetPackageName() : ?string
    {
        return 'red-jasmine/filament-support';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData() : array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getIcons() : array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes() : array
    {
        return [];
    }
}

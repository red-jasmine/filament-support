<?php

namespace RedJasmine\FilamentSupport\Commands;

use Illuminate\Console\Command;

class FilamentSupportCommand extends Command
{
    public $signature = 'filament-support';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}

<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * @var string
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $signature = 'install:pjstarter';

    /**
     * @var string
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $description = 'Install patrikjak/starter package';

    public function handle(): void
    {
        $this->call('install:pjauth');
        $this->call('vendor:publish', ['--tag' => 'pjstarter-assets', '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'pjstarter-views', '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'pjstarter-config', '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'pjstarter-translations', '--force' => true]);
    }
}

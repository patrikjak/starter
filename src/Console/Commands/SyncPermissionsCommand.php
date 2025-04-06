<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Console\Commands;

use Illuminate\Console\Command;
use Patrikjak\Starter\Services\Users\PermissionSynchronizer;

class SyncPermissionsCommand extends Command
{
    /**
     * @var string
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $signature = 'pjstarter:permissions:sync {--force : Force the command to run without confirmation}';

    /**
     * @var string
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $description = 'Sync permissions with the database';

    public function handle(PermissionSynchronizer $permissionSynchronizer): void
    {
        $permissionSynchronizer->synchronize();
    }
}

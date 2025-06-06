<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Console\Commands;

use Illuminate\Console\Command;
use Patrikjak\Starter\Exceptions\Common\ModelIsNotInstanceOfBaseModelException;
use Patrikjak\Starter\Services\Users\PermissionSynchronizer;

class SyncPermissionsCommand extends Command
{
    /**
     * @var string
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $signature = 'pjstarter:permissions:sync';

    /**
     * @var string
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $description = 'Sync permissions with the database';

    /**
     * @throws ModelIsNotInstanceOfBaseModelException
     */
    public function handle(PermissionSynchronizer $permissionSynchronizer): void
    {
        $permissionSynchronizer->synchronize();
    }
}

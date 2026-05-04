<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Exceptions\Users;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class InvitationUpdateFailedException extends NotFoundHttpException
{
    public function __construct(string $email)
    {
        parent::__construct(sprintf('Failed to update invitation for %s', $email));
    }
}

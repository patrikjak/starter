<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Exceptions\Users;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class InvitationDeleteFailedException extends NotFoundHttpException
{
    public function __construct(string $email)
    {
        parent::__construct(sprintf('Failed to delete invitation for %s.', $email));
    }
}

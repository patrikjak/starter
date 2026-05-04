<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Exceptions\Content;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final class ContentAccessDeniedException extends RuntimeException implements HttpExceptionInterface
{
    public function __construct(string $modelClass)
    {
        parent::__construct(sprintf(
            "User does not have create or edit permission for '%s'",
            $modelClass,
        ));
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_FORBIDDEN;
    }

    /**
     * @return array<string, mixed>
     */
    public function getHeaders(): array
    {
        return [];
    }
}

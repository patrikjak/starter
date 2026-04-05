<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Exceptions\Content;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final class ContentContextNotFoundException extends RuntimeException implements HttpExceptionInterface
{
    public function __construct(string $key)
    {
        parent::__construct(sprintf("Content context '%s' not found in registry.", $key));
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }

    /**
     * @return array<string, mixed>
     */
    public function getHeaders(): array
    {
        return [];
    }
}

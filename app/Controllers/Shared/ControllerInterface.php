<?php

declare(strict_types=1);

namespace App\Controllers\Shared;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface ControllerInterface
{
    /** @param array<string, string> $arguments */
    public function __invoke(Request $request, Response $response, array $arguments): Response;
}

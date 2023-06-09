<?php

declare(strict_types=1);

namespace App\Middleware\Authorization;

use App\Config\Config;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpUnauthorizedException;

final readonly class AuthorizationMiddleware implements MiddlewareInterface
{
    public function __construct(public Config $config)
    {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $token = new AuthorizationToken($this->obtainToken($request));
        $hash = $this->config->authorizationTokenHash;
        if (! $token->verify($hash)) {
            throw new HttpUnauthorizedException($request);
        }
        return $handler->handle($request);
    }

    public function obtainToken(Request $request): string
    {
        $header = $request->getHeaderLine('Authorization');
        if (! str_starts_with($header, 'Bearer ')) {
            return '';
        }

        return substr($header, 7);
    }
}

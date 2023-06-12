<?php

/** @noinspection PhpDocMissingThrowsInspection */

declare(strict_types=1);

namespace App\Tests\Functional;

use League\Container\Container;
use LogicException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

abstract class TestCase extends \App\Tests\TestCase
{
    private App $app;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app = require __DIR__ . '/../../bootstrap.php';
    }

    protected function getApp(): App
    {
        return $this->app;
    }

    protected function getContainer(): Container
    {
        $container = $this->app->getContainer();
        if (! $container instanceof Container) {
            throw new LogicException('Container is not set up as ' . Container::class);
        }
        return $container;
    }

    protected function getTestingToken(): string
    {
        $token = $_ENV['AUTHORIZATION_TOKEN_PLAIN'] ?? '';
        if (! is_string($token) || '' === $token) {
            throw new LogicException('Token for testing must be environment defined');
        }
        return $token;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array<string, string|string[]> $headers
     * @return ServerRequestInterface
     */
    protected function createRequest(string $method, string $uri, array $headers = []): ServerRequestInterface
    {
        $request = (new ServerRequestFactory())->createServerRequest($method, $uri);
        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }
        return $request;
    }

    /** @param array<mixed>|null $inputs */
    protected function createFormRequest(
        string $method,
        string $action,
        string $authorizationToken = '',
        array|null $inputs = null,
    ): ServerRequestInterface {
        $request = $this->createRequest($method, $action, array_filter([
            'Authorization' => ($authorizationToken) ? "Bearer $authorizationToken" : null,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ]));
        if (null !== $inputs) {
            $request = $request->withParsedBody($inputs);
        }
        return $request;
    }

    /** @param array<mixed>|null $inputs */
    protected function createJsonRequestAuthorized(
        string $method,
        string $uri,
        array|null $inputs = null,
    ): ServerRequestInterface {
        $inputs = array_merge([
            'certificate' => 'base64:' . base64_encode($this->fileContents('/fiel/EKU9003173C9.cer')),
            'privateKey' => 'base64:' . base64_encode($this->fileContents('/fiel/EKU9003173C9.key')),
            'passphrase' => trim($this->fileContents('/fiel/EKU9003173C9-password.txt')),
        ], $inputs ?? []);
        return $this->createJsonRequest($method, $uri, $this->getTestingToken(), $inputs);
    }

    /** @param array<mixed>|null $inputs */
    protected function createJsonRequest(
        string $method,
        string $uri,
        string $authorizationToken = '',
        array|null $inputs = null,
    ): ServerRequestInterface {
        $request = $this->createRequest($method, $uri, array_filter([
            'Authorization' => ($authorizationToken) ? "Bearer $authorizationToken" : null,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]));
        if (null !== $inputs) {
            $request->getBody()->write((string) json_encode($inputs));
            $request = $request->withParsedBody($inputs);
        }
        return $request;
    }

    protected function createResponse(int $code = 200): ResponseInterface
    {
        return (new ResponseFactory())->createResponse($code);
    }
}

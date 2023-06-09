<?php

declare(strict_types=1);

namespace App\Controllers\Shared;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Factory\StreamFactory;

abstract class BaseController implements ControllerInterface
{
    public function __construct(
        public readonly StreamFactory $streamFactory,
    ) {
    }

    final protected function jsonResponse(
        Response $response,
        int $status = StatusCodeInterface::STATUS_OK,
        object|null $data = null,
    ): Response {
        $jsonData = null !== $data ? (string) json_encode($data) : '';
        $responseStream = $this->streamFactory->createStream($jsonData);
        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($responseStream)
        ;
    }
}

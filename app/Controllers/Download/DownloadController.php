<?php

declare(strict_types=1);

namespace App\Controllers\Download;

use App\Controllers\Shared\BaseController;
use App\Controllers\Shared\SatWsServiceFactory;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Factory\StreamFactory;
use Throwable;

final class DownloadController extends BaseController
{
    public function __construct(
        public readonly DownloadInputsBuilder $inputsBuilder,
        public readonly SatWsServiceFactory $satWsServiceFactory,
        StreamFactory $streamFactory,
    ) {
        parent::__construct($streamFactory);
    }

    public function __invoke(Request $request, Response $response, array $arguments): Response
    {
        try {
            $inputs = $this->inputsBuilder->build($request);
        } catch (Throwable $exception) {
            return $this->jsonResponse(
                $response,
                StatusCodeInterface::STATUS_BAD_REQUEST,
                data: (object) ['error' => $exception->getMessage()],
            );
        }

        try {
            $service = $this->satWsServiceFactory->create($inputs->credential, $inputs->token);
            $result = $service->download($inputs->packageId);
            if ('' === $result->getPackageContent()) {
                return $this->jsonResponse($response, StatusCodeInterface::STATUS_NOT_FOUND, data: $result);
            }
        } catch (Throwable $exception) {
            return $this->jsonResponse(
                $response,
                StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR,
                data: (object) ['error' => $exception->getMessage()],
            );
        }

        $response->getBody()->write($result->getPackageContent());
        return $response;
    }
}

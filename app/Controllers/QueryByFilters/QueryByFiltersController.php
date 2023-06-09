<?php

declare(strict_types=1);

namespace App\Controllers\QueryByFilters;

use App\Controllers\Shared\BaseController;
use App\Controllers\Shared\SatWsServiceFactory;
use Fig\Http\Message\StatusCodeInterface;
use PhpCfdi\SatWsDescargaMasiva\Services\Query\QueryParameters;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Factory\StreamFactory;
use Throwable;

final class QueryByFiltersController extends BaseController
{
    public function __construct(
        public readonly QueryByFiltersInputsBuilder $inputsBuilder,
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
            $parameters = QueryParameters::create($inputs->period)
                ->withRequestType($inputs->requestType)
                ->withDownloadType($inputs->downloadType)
                ->withDocumentType($inputs->documentType)
                ->withComplement($inputs->complement)
                ->withDocumentStatus($inputs->documentStatus)
                ->withRfcOnBehalf($inputs->rfcOnBehalf)
                ->withRfcMatch($inputs->rfcMatch)
            ;
            $service = $this->satWsServiceFactory->create($inputs->credential, $inputs->token);
            $result = $service->query($parameters);
        } catch (Throwable $exception) {
            return $this->jsonResponse(
                $response,
                StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR,
                data: (object) ['error' => $exception->getMessage()],
            );
        }

        return $this->jsonResponse($response, data: (object) [
            'result' => $result,
            'token' => $service->currentToken,
        ]);
    }
}

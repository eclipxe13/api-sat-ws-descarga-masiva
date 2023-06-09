<?php

declare(strict_types=1);

namespace App\Controllers\Complements;

use App\Controllers\Shared\BaseController;
use PhpCfdi\SatWsDescargaMasiva\Shared\ComplementoRetenciones;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class ComplementsRetentionController extends BaseController
{
    public function __invoke(Request $request, Response $response, array $arguments): Response
    {
        return $this->jsonResponse(
            $response,
            data: (object) ComplementoRetenciones::getLabels(),
        );
    }
}

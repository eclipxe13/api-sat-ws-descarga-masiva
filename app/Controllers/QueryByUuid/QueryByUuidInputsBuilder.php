<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace App\Controllers\QueryByUuid;

use App\Controllers\Shared\WithCredentialInputBuilderTrait;
use Exception;
use PhpCfdi\SatWsDescargaMasiva\Shared\RequestType;
use PhpCfdi\SatWsDescargaMasiva\Shared\Uuid;
use Psr\Http\Message\ServerRequestInterface as Request;

final class QueryByUuidInputsBuilder
{
    use WithCredentialInputBuilderTrait;

    /** @throws Exception */
    public function build(Request $request): QueryByUuidInputs
    {
        $this->setUpInputsFromRequest($request);

        return new QueryByUuidInputs(
            $this->buildCredential(),
            $this->buildToken(),
            $this->buildUuid(),
            $this->buildRequestType(),
        );
    }

    private function buildUuid(): Uuid
    {
        return Uuid::create($this->getString('uuid'));
    }

    private function buildRequestType(): RequestType
    {
        $value = $this->getString('requestType');
        if ('' === $value) {
            return RequestType::metadata();
        }

        return new RequestType($value);
    }
}

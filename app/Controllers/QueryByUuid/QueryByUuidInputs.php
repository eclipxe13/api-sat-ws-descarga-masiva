<?php

declare(strict_types=1);

namespace App\Controllers\QueryByUuid;

use PhpCfdi\Credentials\Credential;
use PhpCfdi\SatWsDescargaMasiva\Shared\RequestType;
use PhpCfdi\SatWsDescargaMasiva\Shared\Token;
use PhpCfdi\SatWsDescargaMasiva\Shared\Uuid;

final readonly class QueryByUuidInputs
{
    public function __construct(
        public Credential $credential,
        public Token|null $token,
        public Uuid $uuid,
        public RequestType $requestType,
    ) {
    }
}

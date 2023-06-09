<?php

declare(strict_types=1);

namespace App\Controllers\Verify;

use LogicException;
use PhpCfdi\Credentials\Credential;
use PhpCfdi\SatWsDescargaMasiva\Shared\Token;

final class VerifyInputs
{
    public function __construct(
        public Credential $credential,
        public Token|null $token,
        public string $requestId,
    ) {
        if ('' === $this->requestId) {
            throw new LogicException('Invalid argument requestId');
        }
    }
}

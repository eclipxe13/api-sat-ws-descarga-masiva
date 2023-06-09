<?php

declare(strict_types=1);

namespace App\Controllers\Download;

use LogicException;
use PhpCfdi\Credentials\Credential;
use PhpCfdi\SatWsDescargaMasiva\Shared\Token;

final class DownloadInputs
{
    public function __construct(
        public Credential $credential,
        public Token|null $token,
        public string $packageId,
    ) {
        if ('' === $this->packageId) {
            throw new LogicException('Invalid argument packageId');
        }
    }
}

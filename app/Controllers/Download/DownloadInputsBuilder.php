<?php

declare(strict_types=1);

namespace App\Controllers\Download;

use App\Controllers\Shared\WithCredentialInputBuilderTrait;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;

final class DownloadInputsBuilder
{
    use WithCredentialInputBuilderTrait;

    /** @throws Exception */
    public function build(Request $request): DownloadInputs
    {
        $this->setUpInputsFromRequest($request);

        return new DownloadInputs(
            $this->buildCredential(),
            $this->buildToken(),
            $this->buildPackageId(),
        );
    }

    private function buildPackageId(): string
    {
        return $this->getString('packageId');
    }
}

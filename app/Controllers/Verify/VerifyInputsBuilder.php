<?php

declare(strict_types=1);

namespace App\Controllers\Verify;

use App\Controllers\Shared\WithCredentialInputBuilderTrait;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;

final class VerifyInputsBuilder
{
    use WithCredentialInputBuilderTrait;

    /** @throws Exception */
    public function build(Request $request): VerifyInputs
    {
        $this->setUpInputs($request, ['certificate', 'privateKey']);

        return new VerifyInputs(
            $this->buildCredential(),
            $this->buildToken(),
            $this->buildRequestId(),
        );
    }

    private function buildRequestId(): string
    {
        return $this->getString('requestId');
    }
}

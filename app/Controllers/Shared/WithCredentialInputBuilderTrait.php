<?php

declare(strict_types=1);

namespace App\Controllers\Shared;

use Exception;
use PhpCfdi\Credentials\Credential;
use PhpCfdi\SatWsDescargaMasiva\Shared\DateTime;
use PhpCfdi\SatWsDescargaMasiva\Shared\Token;
use Throwable;

trait WithCredentialInputBuilderTrait
{
    use InputsTrait;

    /** @throws Exception */
    private function buildCredential(): Credential
    {
        try {
            $certificate = $this->getString('certificate');
            $privateKey = $this->getString('privateKey');
            $passPhrase = $this->getString('passphrase');
            return Credential::create($certificate, $privateKey, $passPhrase);
        } catch (Throwable $exception) {
            throw new Exception(
                'Unable to create a credential: ' . $exception->getMessage(),
                previous: $exception,
            );
        }
    }

    private function buildToken(): Token|null
    {
        $data = $this->getStrings('token');
        if (! isset($data['created'], $data['expires'], $data['value'])) {
            return null;
        }

        return new Token(
            DateTime::create($data['created']),
            DateTime::create($data['expires']),
            $data['value'],
        );
    }
}

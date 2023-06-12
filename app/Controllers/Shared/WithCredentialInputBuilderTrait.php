<?php

declare(strict_types=1);

namespace App\Controllers\Shared;

use Exception;
use PhpCfdi\Credentials\Credential;
use PhpCfdi\SatWsDescargaMasiva\Shared\Token;
use Throwable;

trait WithCredentialInputBuilderTrait
{
    use InputsTrait;

    /** @throws Exception */
    private function buildCredential(): Credential
    {
        try {
            $certificate = $this->getString('certificate', allowBase64: true);
            $privateKey = $this->getString('privateKey', allowBase64: true);
            $passPhrase = $this->getString('passphrase');
            $credential = Credential::create($certificate, $privateKey, $passPhrase);
            if (! $credential->isFiel()) {
                throw new Exception(
                    sprintf('The certificate %s is not a FIEL', $credential->certificate()->serialNumber()->decimal()),
                );
            }
            if (! $credential->certificate()->validOn()) {
                throw new Exception(
                    sprintf('The certificate %s is expired', $credential->certificate()->serialNumber()->decimal()),
                );
            }
            return $credential;
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
            $this->buildDateTimeFromValue($data['created']),
            $this->buildDateTimeFromValue($data['expires']),
            $data['value'],
        );
    }
}

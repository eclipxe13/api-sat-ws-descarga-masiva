<?php

declare(strict_types=1);

namespace App\Controllers\Shared;

use PhpCfdi\Credentials\Credential;
use PhpCfdi\SatWsDescargaMasiva\RequestBuilder\FielRequestBuilder\Fiel;
use PhpCfdi\SatWsDescargaMasiva\RequestBuilder\FielRequestBuilder\FielRequestBuilder;
use PhpCfdi\SatWsDescargaMasiva\Service;
use PhpCfdi\SatWsDescargaMasiva\Shared\Token;
use PhpCfdi\SatWsDescargaMasiva\WebClient\GuzzleWebClient;

class SatWsServiceFactory
{
    public function create(Credential $credential, Token|null $token): Service
    {
        return new Service(
            new FielRequestBuilder(new Fiel($credential)),
            new GuzzleWebClient(),
            $token,
        );
    }
}

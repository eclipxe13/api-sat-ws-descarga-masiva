<?php

declare(strict_types=1);

namespace App\Controllers\QueryByFilters;

use PhpCfdi\Credentials\Credential;
use PhpCfdi\SatWsDescargaMasiva\Shared\ComplementoInterface;
use PhpCfdi\SatWsDescargaMasiva\Shared\DateTimePeriod;
use PhpCfdi\SatWsDescargaMasiva\Shared\DocumentStatus;
use PhpCfdi\SatWsDescargaMasiva\Shared\DocumentType;
use PhpCfdi\SatWsDescargaMasiva\Shared\DownloadType;
use PhpCfdi\SatWsDescargaMasiva\Shared\RequestType;
use PhpCfdi\SatWsDescargaMasiva\Shared\RfcMatch;
use PhpCfdi\SatWsDescargaMasiva\Shared\RfcOnBehalf;
use PhpCfdi\SatWsDescargaMasiva\Shared\Token;

final readonly class QueryByFiltersInputs
{
    public function __construct(
        public Credential $credential,
        public Token|null $token,
        public DateTimePeriod $period,
        public RequestType $requestType,
        public DownloadType $downloadType,
        public DocumentType $documentType,
        public ComplementoInterface $complement,
        public DocumentStatus $documentStatus,
        public RfcOnBehalf $rfcOnBehalf,
        public RfcMatch $rfcMatch,
    ) {
    }
}

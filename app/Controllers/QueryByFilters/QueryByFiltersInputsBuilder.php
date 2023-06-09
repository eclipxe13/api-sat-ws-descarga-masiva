<?php

declare(strict_types=1);

namespace App\Controllers\QueryByFilters;

use App\Controllers\Shared\WithCredentialInputBuilderTrait;
use Exception;
use PhpCfdi\SatWsDescargaMasiva\Shared\ComplementoCfdi;
use PhpCfdi\SatWsDescargaMasiva\Shared\ComplementoInterface;
use PhpCfdi\SatWsDescargaMasiva\Shared\DateTime;
use PhpCfdi\SatWsDescargaMasiva\Shared\DateTimePeriod;
use PhpCfdi\SatWsDescargaMasiva\Shared\DocumentStatus;
use PhpCfdi\SatWsDescargaMasiva\Shared\DocumentType;
use PhpCfdi\SatWsDescargaMasiva\Shared\DownloadType;
use PhpCfdi\SatWsDescargaMasiva\Shared\RequestType;
use PhpCfdi\SatWsDescargaMasiva\Shared\RfcMatch;
use PhpCfdi\SatWsDescargaMasiva\Shared\RfcOnBehalf;
use Psr\Http\Message\ServerRequestInterface as Request;

final class QueryByFiltersInputsBuilder
{
    use WithCredentialInputBuilderTrait;

    /** @throws Exception */
    public function build(Request $request): QueryByFiltersInputs
    {
        $this->setUpInputs($request, ['certificate', 'privateKey']);

        return new QueryByFiltersInputs(
            $this->buildCredential(),
            $this->buildToken(),
            $this->buildPeriod(),
            $this->buildRequestType(),
            $this->buildDownloadType(),
            $this->buildDocumentType(),
            $this->buildComplement(),
            $this->buildDocumentStatus(),
            $this->buildRfcOnBehalf(),
            $this->buildRfcMatch(),
        );
    }

    /** @throws Exception */
    private function buildPeriod(): DateTimePeriod
    {
        return DateTimePeriod::create(
            $this->buildDateTime('since'),
            $this->buildDateTime('until'),
        );
    }

    /** @throws Exception */
    private function buildDateTime(string $key): DateTime
    {
        $value = $this->getString($key);
        if ('' === $value) {
            throw new Exception(sprintf('Missing value for parameter %s', $key));
        }
        if (is_numeric($value)) {
            $value = intval($value);
        }
        return DateTime::create($value);
    }

    private function buildRequestType(): RequestType
    {
        $value = $this->getString('requestType');
        if ('' === $value) {
            return RequestType::metadata();
        }

        return new RequestType($value);
    }

    private function buildDownloadType(): DownloadType
    {
        $value = $this->getString('downloadType');
        if ('' === $value) {
            return DownloadType::issued();
        }

        return new DownloadType($value);
    }

    private function buildDocumentType(): DocumentType
    {
        $value = $this->getString('documentType');
        if ('' === $value) {
            return DocumentType::undefined();
        }

        return new DocumentType($value);
    }

    private function buildComplement(): ComplementoInterface
    {
        $value = $this->getString('complemento');
        if ('' === $value) {
            return ComplementoCfdi::undefined();
        }

        return new ComplementoCfdi($value);
    }

    private function buildDocumentStatus(): DocumentStatus
    {
        $value = $this->getString('documentStatus');
        if ('' === $value) {
            return DocumentStatus::undefined();
        }

        return new DocumentStatus($value);
    }

    private function buildRfcOnBehalf(): RfcOnBehalf
    {
        $value = $this->getString('rfcOnBehalf');
        if ('' === $value) {
            return RfcOnBehalf::empty();
        }

        return RfcOnBehalf::create($value);
    }

    private function buildRfcMatch(): RfcMatch
    {
        $value = $this->getString('rfcOnBehalf');
        if ('' === $value) {
            return RfcMatch::empty();
        }

        return RfcMatch::create($value);
    }
}

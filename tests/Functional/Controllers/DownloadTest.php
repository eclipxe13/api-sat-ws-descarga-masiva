<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controllers;

use App\Controllers\Shared\SatWsServiceFactory;
use App\Tests\Functional\TestCase;
use Fig\Http\Message\StatusCodeInterface;
use PhpCfdi\SatWsDescargaMasiva\Service;
use PhpCfdi\SatWsDescargaMasiva\Services\Download\DownloadResult;
use PhpCfdi\SatWsDescargaMasiva\Shared\StatusCode;
use PHPUnit\Framework\MockObject\MockObject;

/** @see \App\Controllers\Download\DownloadController */
final class DownloadTest extends TestCase
{
    public function testDownloadContents(): void
    {
        $request = $this->createJsonRequestAuthorized('POST', '/download', [
            'packageId' => 'CEE4BE01-8567-4DEB-8421-ADD60F0B3DAC-1',
        ]);

        /** @var Service&MockObject $fakeSatWsService */
        $fakeSatWsService = $this->createMock(Service::class);
        $expectedContents = 'x-contents';
        $preparedResult = new DownloadResult(new StatusCode(5000, 'Solicitud aceptada'), $expectedContents);
        $fakeSatWsService->method('download')->willReturn($preparedResult);

        /** @var SatWsServiceFactory&MockObject $satWsServiceFactory */
        $satWsServiceFactory = $this->createMock(SatWsServiceFactory::class);
        $satWsServiceFactory->method('create')->willReturn($fakeSatWsService);
        $this->getContainer()->add(SatWsServiceFactory::class, $satWsServiceFactory);

        $response = $this->getApp()->handle($request);
        /** @var array<mixed> $responseBody */
        $responseBody = strval($response->getBody());

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertSame($expectedContents, $responseBody);
    }

    public function testDownloadNonExistentPackageId(): void
    {
        $request = $this->createJsonRequestAuthorized('POST', '/download', [
            'packageId' => 'CEE4BE01-8567-4DEB-8421-ADD60F0B3DAC-1',
        ]);
        $response = $this->getApp()->handle($request);
        /** @var array<mixed> $responseBody */
        $responseBody = json_decode(strval($response->getBody()), associative: true);

        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
        $this->assertSame([
            'result' => [
                'status' => ['code', 'message'],
                'size',
            ],
            'token' => ['created', 'expires', 'value'],
        ], $this->arrayStructure($responseBody));
    }

    public function testDownloadInvalid(): void
    {
        $request = $this->createJsonRequestAuthorized('POST', '/download');
        $response = $this->getApp()->handle($request);
        /** @var array<mixed> $responseBody */
        $responseBody = json_decode(strval($response->getBody()), associative: true);

        $this->assertSame(StatusCodeInterface::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertArrayHasKey('error', $responseBody);
    }
}

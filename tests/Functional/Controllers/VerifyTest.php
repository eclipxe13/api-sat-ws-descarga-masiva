<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controllers;

use App\Tests\Functional\TestCase;
use Fig\Http\Message\StatusCodeInterface;

/** @see \App\Controllers\Verify\VerifyController */
final class VerifyTest extends TestCase
{
    public function testVerifyHappyPath(): void
    {
        $request = $this->createJsonRequestAuthorized('POST', '/verify', [
            'requestId' => 'CEE4BE01-8567-4DEB-8421-ADD60F0B3DAC',
        ]);
        $response = $this->getApp()->handle($request);
        /** @var array<mixed> $responseBody */
        $responseBody = json_decode(strval($response->getBody()), associative: true);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertSame([
            'result' => [
                'status' => ['code', 'message'],
                'codeRequest' => ['value', 'message'],
                'statusRequest' => ['value', 'message'],
                'numberCfdis',
                'packagesIds' => [],
            ],
            'token' => ['created', 'expires', 'value'],
        ], $this->arrayStructure($responseBody));
    }

    public function testVerifyInvalid(): void
    {
        $request = $this->createJsonRequestAuthorized('POST', '/verify');
        $response = $this->getApp()->handle($request);
        /** @var array<mixed> $responseBody */
        $responseBody = json_decode(strval($response->getBody()), associative: true);

        $this->assertSame(StatusCodeInterface::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertArrayHasKey('error', $responseBody);
    }
}

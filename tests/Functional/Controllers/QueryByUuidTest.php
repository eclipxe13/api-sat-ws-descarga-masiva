<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controllers;

use App\Tests\Functional\TestCase;
use Fig\Http\Message\StatusCodeInterface;

/** @see \App\Controllers\QueryByUuid\QueryByUuidController */
final class QueryByUuidTest extends TestCase
{
    public function testQueryByUuidHappyPath(): void
    {
        $request = $this->createJsonRequestAuthorized('POST', '/query-by-uuid', [
            'uuid' => 'CEE4BE01-8567-4DEB-8421-ADD60F0B3DAC',
        ]);
        $response = $this->getApp()->handle($request);
        /** @var array<mixed> $responseBody */
        $responseBody = json_decode(strval($response->getBody()), associative: true);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertArrayHasKey('requestId', $responseBody);
        $this->assertArrayHasKey('status', $responseBody);
    }

    public function testQueryByUuidInvalid(): void
    {
        $request = $this->createJsonRequestAuthorized('POST', '/query-by-uuid');
        $response = $this->getApp()->handle($request);
        /** @var array<mixed> $responseBody */
        $responseBody = json_decode(strval($response->getBody()), associative: true);

        $this->assertSame(StatusCodeInterface::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertArrayHasKey('error', $responseBody);
    }
}

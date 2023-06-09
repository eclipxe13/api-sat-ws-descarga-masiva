<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controllers;

use App\Tests\Functional\TestCase;
use Fig\Http\Message\StatusCodeInterface;

/** @see \App\Controllers\QueryByFilters\QueryByFiltersController */
final class QueryByFiltersTest extends TestCase
{
    public function testQueryByFiltersHappyPath(): void
    {
        $request = $this->createJsonRequestAuthorized('POST', '/query-by-filters', [
            'since' => '2022-01-02 03:04:05',
            'until' => '2022-12-31 23:59:58',
        ]);
        $response = $this->getApp()->handle($request);
        /** @var array<mixed> $responseBody */
        $responseBody = json_decode(strval($response->getBody()), associative: true);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertSame([
            'result' => [
                'status' => ['code', 'message'],
                'requestId',
            ],
            'token' => ['created', 'expires', 'value'],
        ], $this->arrayStructure($responseBody));
    }

    public function testQueryByFiltersInvalid(): void
    {
        $request = $this->createJsonRequestAuthorized('POST', '/query-by-filters');
        $response = $this->getApp()->handle($request);
        /** @var array<mixed> $responseBody */
        $responseBody = json_decode(strval($response->getBody()), associative: true);

        $this->assertSame(StatusCodeInterface::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertArrayHasKey('error', $responseBody);
    }
}

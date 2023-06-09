<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controllers;

use App\Tests\Functional\TestCase;
use Fig\Http\Message\StatusCodeInterface;
use PhpCfdi\SatWsDescargaMasiva\Shared\ComplementoRetenciones;

/** @see \App\Controllers\Complements\ComplementsRetentionController */
final class ComplementsRetentionTest extends TestCase
{
    public function testComplementsRetention(): void
    {
        $request = $this->createJsonRequest('GET', '/complements/retention', $this->getTestingToken());
        $response = $this->getApp()->handle($request);
        /** @var array<mixed> $responseBody */
        $responseBody = json_decode(strval($response->getBody()), associative: true);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertEquals(ComplementoRetenciones::getLabels(), $responseBody);
    }
}

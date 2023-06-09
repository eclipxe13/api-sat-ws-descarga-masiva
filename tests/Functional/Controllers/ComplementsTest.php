<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controllers;

use App\Tests\Functional\TestCase;
use Fig\Http\Message\StatusCodeInterface;
use PhpCfdi\SatWsDescargaMasiva\Shared\ComplementoCfdi;

/** @see \App\Controllers\Complements\ComplementsController */
final class ComplementsTest extends TestCase
{
    public function testComplements(): void
    {
        $request = $this->createJsonRequest('GET', '/complements-cfdi', $this->getTestingToken());
        $response = $this->getApp()->handle($request);
        /** @var array<mixed> $responseBody */
        $responseBody = json_decode(strval($response->getBody()), associative: true);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertEquals(ComplementoCfdi::getLabels(), $responseBody);
    }
}

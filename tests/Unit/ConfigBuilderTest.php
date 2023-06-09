<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Config\ConfigBuilder;
use App\Tests\TestCase;

final class ConfigBuilderTest extends TestCase
{
    public function testBuildWithNoEnvironment(): void
    {
        $builder = new ConfigBuilder([]);
        $config = $builder->build();

        $this->assertSame('', $config->authorizationTokenHash);
    }

    public function testBuildWithData(): void
    {
        $builder = new ConfigBuilder([
            'AUTHORIZATION_TOKEN' => 'x-token',
        ]);
        $config = $builder->build();

        $this->assertSame('x-token', $config->authorizationTokenHash);
    }
}

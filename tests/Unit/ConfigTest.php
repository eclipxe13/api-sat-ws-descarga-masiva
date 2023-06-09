<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Config\Config;
use App\Middleware\Authorization\AuthorizationToken;
use App\Tests\TestCase;

final class ConfigTest extends TestCase
{
    public function testConfigValues(): void
    {
        $authorizationTokenHash = AuthorizationToken::createRandom()->getHash();

        $config = new Config(
            $authorizationTokenHash,
        );

        $this->assertSame($authorizationTokenHash, $config->authorizationTokenHash);
    }
}

<?php

declare(strict_types=1);

namespace App\Config;

final readonly class Config
{
    public function __construct(
        public string $authorizationTokenHash,
    ) {
    }
}

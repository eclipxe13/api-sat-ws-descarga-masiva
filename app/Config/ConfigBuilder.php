<?php

declare(strict_types=1);

namespace App\Config;

class ConfigBuilder
{
    /** @var array<string, mixed> */
    private array $values;

    /** @param array<string, mixed> $environment */
    public function __construct(array $environment)
    {
        $this->values = $environment;
    }

    public function build(): Config
    {
        return new Config(
            $this->getValueAsString('AUTHORIZATION_TOKEN'),
        );
    }

    /** @noinspection PhpSameParameterValueInspection */
    private function getValueAsString(string $key): string
    {
        $value = $this->values[$key] ?? '';
        if (! is_string($value)) {
            $value = (is_scalar($value)) ? strval($value) : null;
        }
        return $value ?? '';
    }
}

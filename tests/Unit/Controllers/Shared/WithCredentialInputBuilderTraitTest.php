<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controllers\Shared;

use App\Controllers\Shared\WithCredentialInputBuilderTrait;
use App\Tests\TestCase;
use PhpCfdi\SatWsDescargaMasiva\Shared\DateTime;
use PhpCfdi\SatWsDescargaMasiva\Shared\Token;

final class WithCredentialInputBuilderTraitTest extends TestCase
{
    public function testBuildToken(): void
    {
        $expires = time() - 1;
        $created = $expires - 600;
        $value = 'x-value';
        $expectedToken = new Token(DateTime::create($created), DateTime::create($expires), $value);

        $implementor = new class () {
            use WithCredentialInputBuilderTrait;

            /** @param array<mixed> $inputs */
            public function exposeBuildToken(array $inputs): Token|null
            {
                $this->setUpInputs($inputs);
                return $this->buildToken();
            }
        };

        $token = $implementor->exposeBuildToken([
            'token' => [
                'created' => $created,
                'expires' => $expires,
                'value' => $value,
            ],
        ]);

        $this->assertEquals($expectedToken, $token);
    }
}

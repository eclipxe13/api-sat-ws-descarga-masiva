#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Middleware\Authorization\AuthorizationToken;

$token = AuthorizationToken::createRandom();

echo <<< EOT
    Set up the environment with AUTHORIZATION_TOKEN={$token->getHash()}',
    Your client must use the HTTP authorization header:
       Authorization: Bearer {$token->getToken()}

    EOT;

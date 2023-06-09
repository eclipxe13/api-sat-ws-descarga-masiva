<?php

declare(strict_types=1);

use App\Config\Config;
use App\Config\ConfigBuilder;
use App\Controllers\Complements\ComplementsController;
use App\Controllers\Download\DownloadController;
use App\Controllers\QueryByFilters\QueryByFiltersController;
use App\Controllers\QueryByUuid\QueryByUuidController;
use App\Controllers\Verify\VerifyController;
use App\Middleware\Authorization\AuthorizationMiddleware;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Handlers\ErrorHandler;

return (function (): App {
    $container = new Container();
    $container->delegate(new ReflectionContainer());
    $container->add(Config::class, static fn (): Config => (new ConfigBuilder($_ENV))->build());

    $app = AppFactory::createFromContainer($container);

    /** @var AuthorizationMiddleware $authorizationMiddleware */
    $authorizationMiddleware = $container->get(AuthorizationMiddleware::class);
    $app->add($authorizationMiddleware);

    $errorMiddleware = $app->addErrorMiddleware(displayErrorDetails: true, logErrors: false, logErrorDetails: false);
    /** @var ErrorHandler $errorHandler */
    $errorHandler = $errorMiddleware->getDefaultErrorHandler();
    $errorHandler->forceContentType('application/json');

    $app->get('/complements-cfdi', ComplementsController::class);
    $app->post('/query-by-uuid', QueryByUuidController::class);
    $app->post('/query-by-filters', QueryByFiltersController::class);
    $app->post('/verify', VerifyController::class);
    $app->post('/download', DownloadController::class);

    return $app;
})();

<?php

use Brotkrueml\FormRateLimit\Extension;
use Brotkrueml\FormRateLimit\RateLimiter\FormRateLimitFactory;
use Brotkrueml\FormRateLimit\RateLimiter\Storage\FileStorage;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\Core\Environment;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function(ContainerConfigurator $configurator) {
    $services = $configurator->services();
    $services
        ->defaults()
        ->autoconfigure()
        ->autowire()
        ->private();

    $services
        ->load('Brotkrueml\\FormRateLimit\\', '../Classes/*')
        ->exclude('../Classes/{Domain/Dto,Extension.php}');

    $services->set(FileStorage::class)
        ->arg('$storagePath', Environment::getVarPath() . '/' . Extension::KEY);

    $services->set(FormRateLimitFactory::class)
        ->arg('$storage', service(FileStorage::class));
};
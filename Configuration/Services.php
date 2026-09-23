<?php

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Brotkrueml\FormRateLimit\Extension;
use Brotkrueml\FormRateLimit\RateLimiter\FormRateLimitFactory;
use Brotkrueml\FormRateLimit\RateLimiter\Storage\FileStorage;
use Brotkrueml\FormRateLimit\RateLimiter\Storage\FileStorageCleaner;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\Core\Environment;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services();
    $services
        ->defaults()
        ->autoconfigure()
        ->autowire()
        ->private();

    $services
        ->load('Brotkrueml\FormRateLimit\\', '../Classes/*')
        ->exclude('../Classes/{Domain/Dto,Extension.php}');

    $storagePath = Environment::getVarPath() . '/' . Extension::KEY;

    $services->set(FileStorage::class)
        ->arg('$storagePath', $storagePath);

    $services->set(FileStorageCleaner::class)
        ->arg('$storagePath', $storagePath);

    $services->set(FormRateLimitFactory::class)
        ->arg('$storage', service(FileStorage::class));
};

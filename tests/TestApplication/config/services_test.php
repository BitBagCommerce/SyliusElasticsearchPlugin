<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $env = $_ENV['APP_ENV'] ?? 'dev';

    if (str_starts_with($env, 'test')) {
        $syliusBehatServices = '../../../vendor/sylius/sylius/src/Sylius/Behat/Resources/config/services';
        $container->import(is_file(__DIR__ . '/' . $syliusBehatServices . '.php') ? $syliusBehatServices . '.php' : $syliusBehatServices . '.xml');
        $container->import('@BitBagSyliusElasticsearchPlugin/tests/Behat/Resources/services.php');

        // workaround needed for strange "test.client.history" problem
        // see https://github.com/FriendsOfBehat/SymfonyExtension/issues/88
        $container->services()->alias(AbstractBrowser::class, 'test.client');
    }
};

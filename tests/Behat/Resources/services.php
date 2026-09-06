<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Tests\BitBag\SyliusElasticsearchPlugin\Behat\Service\Populate;

return static function (ContainerConfigurator $container): void {
    $container->import('services/**/*.php');

    $services = $container->services()
        ->defaults()
        ->public();

    $services->set('bitbag.sylius_elasticsearch_plugin.behat.populate', Populate::class)
        ->args([
            service('event_dispatcher'),
            service('fos_elastica.index_manager'),
            service('fos_elastica.pager_provider_registry'),
            service('fos_elastica.pager_persister_registry'),
            service('fos_elastica.resetter'),
        ]);

    $services->alias('bitbag.test.client', 'test.client');
    $services->alias('bitbag.test.client.history', 'test.client.history');
    $services->alias('bitbag.test.client.cookiejar', 'test.client.cookiejar');
};

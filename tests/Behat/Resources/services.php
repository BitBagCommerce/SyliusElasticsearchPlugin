<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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

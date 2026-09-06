<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use BitBag\SyliusElasticsearchPlugin\Api\DataProvider\ProductCollectionDataProvider;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('bitbag_sylius_elasticsearch_plugin.api.data_provider.product_collection', ProductCollectionDataProvider::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.api.request_data_handler'),
            service('bitbag_sylius_elasticsearch_plugin.finder.search_products'),
            service('bitbag_sylius_elasticsearch_plugin.api.resolver.facets'),
        ])
        ->tag('api_platform.state_provider', ['priority' => 10]);
};

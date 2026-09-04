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

use BitBag\SyliusElasticsearchPlugin\Api\Resolver\FacetsResolver;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('bitbag_sylius_elasticsearch_plugin.api.resolver.facets', FacetsResolver::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.facet.auto_registry'),
            service('bitbag_sylius_elasticsearch_plugin.query_builder.site_wide_products'),
            service('bitbag_sylius_elasticsearch_plugin.facet.registry'),
            service('fos_elastica.finder.bitbag_shop_product'),
        ]);
};

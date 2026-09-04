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

use BitBag\SyliusElasticsearchPlugin\Facet\AutoDiscoverRegistry;
use BitBag\SyliusElasticsearchPlugin\Facet\PriceFacet;
use BitBag\SyliusElasticsearchPlugin\Facet\Registry;
use BitBag\SyliusElasticsearchPlugin\Facet\TaxonFacet;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('bitbag_sylius_elasticsearch_plugin.facet.price', PriceFacet::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.property_name_resolver.channel_pricing'),
            service('sylius.formatter.money'),
            service('sylius.context.shopper'),
            service('sylius.converter.currency'),
            '%bitbag_es_shop_price_facet_interval%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.facet.taxon', TaxonFacet::class)
        ->args([
            service('sylius.repository.taxon'),
            '%bitbag_es_shop_product_taxons_property%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.facet.registry', Registry::class);

    $services->set('bitbag_sylius_elasticsearch_plugin.facet.auto_registry', AutoDiscoverRegistry::class)
        ->args([
            '%bitbag_es_facets_auto_discover%',
            service('bitbag.sylius_elasticsearch_plugin.repository.product_attribute_repository'),
            service('bitbag.sylius_elasticsearch_plugin.repository.product_option_repository'),
            service('bitbag_sylius_elasticsearch_plugin.property_name_resolver.attribute'),
            service('bitbag_sylius_elasticsearch_plugin.property_name_resolver.option'),
            service('sylius.context.locale'),
            service('bitbag_sylius_elasticsearch_plugin.facet.registry'),
            '%bitbag_es_excluded_facet_attributes%',
            '%bitbag_es_excluded_facet_options%',
        ]);
};

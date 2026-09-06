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

use BitBag\SyliusElasticsearchPlugin\Finder\NamedProductsFinder;
use BitBag\SyliusElasticsearchPlugin\Finder\ProductAttributesFinder;
use BitBag\SyliusElasticsearchPlugin\Finder\ProductOptionsFinder;
use BitBag\SyliusElasticsearchPlugin\Finder\ShopProductsFinder;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('bitbag_sylius_elasticsearch_plugin.finder.named_products', NamedProductsFinder::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.query_builder.site_wide_products'),
            service('fos_elastica.finder.bitbag_shop_product'),
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.finder.shop_products', ShopProductsFinder::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.query_builder.taxon_products'),
            service('fos_elastica.finder.bitbag_shop_product'),
            service('bitbag_sylius_elasticsearch_plugin.facet.registry'),
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.finder.search_products', ShopProductsFinder::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.query_builder.site_wide_products'),
            service('fos_elastica.finder.bitbag_shop_product'),
            service('bitbag_sylius_elasticsearch_plugin.facet.registry'),
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.finder.product_options', ProductOptionsFinder::class)
        ->args([
            service('fos_elastica.finder.bitbag_option_taxons'),
            service('bitbag_sylius_elasticsearch_plugin.query_builder.product_options_by_taxon'),
            '%bitbag_es_shop_option_taxons_property%',
            '%bitbag_es_filter_options_max%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.finder.product_attributes', ProductAttributesFinder::class)
        ->args([
            service('fos_elastica.finder.bitbag_attribute_taxons'),
            service('bitbag_sylius_elasticsearch_plugin.query_builder.product_attributes_by_taxon'),
            '%bitbag_es_shop_attribute_taxons_property%',
            '%bitbag_es_filter_attributes_max%',
        ]);
};

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

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolver;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\PriceNameResolver;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\SearchPropertyNameResolverRegistry;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\SearchPropertyNameResolverRegistryInterface;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('bitbag_sylius_elasticsearch_plugin.property_name_resolver.name', ConcatedNameResolver::class)
        ->args([
            '%bitbag_es_shop_name_property_prefix%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.property_name_resolver.option', ConcatedNameResolver::class)
        ->args([
            '%bitbag_es_shop_option_property_prefix%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.property_name_resolver.attribute', ConcatedNameResolver::class)
        ->args([
            '%bitbag_es_shop_attribute_property_prefix%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.property_name_resolver.channel_pricing', ConcatedNameResolver::class)
        ->args([
            '%bitbag_es_shop_product_price_property_prefix%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.property_name_resolver.price', PriceNameResolver::class)
        ->args([
            '%bitbag_es_shop_product_price_property_prefix%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.property_name_resolver.description', ConcatedNameResolver::class)
        ->args([
            '%bitbag_es_shop_description_property_prefix%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.property_name_resolver.short_description', ConcatedNameResolver::class)
        ->args([
            '%bitbag_es_shop_short_description_property_prefix%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.search_property_name_resolver_registry', SearchPropertyNameResolverRegistry::class)
        ->call('addPropertyNameResolver', [
            service('bitbag_sylius_elasticsearch_plugin.property_name_resolver.name'),
        ])
        ->call('addPropertyNameResolver', [
            service('bitbag_sylius_elasticsearch_plugin.property_name_resolver.short_description'),
        ])
        ->call('addPropertyNameResolver', [
            service('bitbag_sylius_elasticsearch_plugin.property_name_resolver.description'),
        ]);

    $services->alias(SearchPropertyNameResolverRegistryInterface::class, 'bitbag_sylius_elasticsearch_plugin.search_property_name_resolver_registry');

    $services->set('bitbag_sylius_elasticsearch_plugin.property_name_resolver.taxon_position', ConcatedNameResolver::class)
        ->args([
            '%bitbag_es_shop_taxon_position_property_prefix%',
        ]);
};

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

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\AttributesQueryBuilder\AttributesTypeDateQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\AttributesQueryBuilder\AttributesTypeNumberQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\AttributesQueryBuilder\AttributesTypeTextQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\ContainsNameQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\FormQueryBuilder\SiteWideFacetsQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\FormQueryBuilder\TaxonFacetsQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\HasAttributesQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\HasChannelQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\HasOptionsQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\HasPriceBetweenQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\HasTaxonQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\IsEnabledQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\ProductAttributesByTaxonQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\ProductOptionsByTaxonQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\ProductsByPartialNameQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\SiteWideProductsQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\TaxonProductsQueryBuilder;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('bitbag_sylius_elasticsearch_plugin.query_builder.is_enabled', IsEnabledQueryBuilder::class)
        ->args([
            '%bitbag_es_shop_enabled_property%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.query_builder.has_channel', HasChannelQueryBuilder::class)
        ->args([
            service('sylius.context.channel'),
            '%bitbag_es_shop_channels_property%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.query_builder.contains_name', ContainsNameQueryBuilder::class)
        ->args([
            service('sylius.context.locale'),
            service('bitbag_sylius_elasticsearch_plugin.search_property_name_resolver_registry'),
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.query_builder.has_product_taxon', HasTaxonQueryBuilder::class)
        ->args([
            '%bitbag_es_shop_product_taxons_property%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.query_builder.has_option_taxon', HasTaxonQueryBuilder::class)
        ->args([
            '%bitbag_es_shop_option_taxons_property%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.query_builder.has_attribute_taxon', HasTaxonQueryBuilder::class)
        ->args([
            '%bitbag_es_shop_attribute_taxons_property%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.query_builder.has_options', HasOptionsQueryBuilder::class);

    $services->set('bitbag_sylius_elasticsearch_plugin.query_builder.has_attributes', HasAttributesQueryBuilder::class)
        ->args([
            service('sylius.context.locale'),
            service('bitbag.sylius_elasticsearch_plugin.repository.product_attribute_repository'),
            tagged_iterator('bitbag_sylius_elasticsearch_plugin.query_builder.attributes'),
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.query_builder.attributes.type.text', AttributesTypeTextQueryBuilder::class)
        ->tag('bitbag_sylius_elasticsearch_plugin.query_builder.attributes');

    $services->set('bitbag_sylius_elasticsearch_plugin.query_builder.attributes.type.number', AttributesTypeNumberQueryBuilder::class)
        ->tag('bitbag_sylius_elasticsearch_plugin.query_builder.attributes');

    $services->set('bitbag_sylius_elasticsearch_plugin.query_builder.attributes.type.date', AttributesTypeDateQueryBuilder::class)
        ->tag('bitbag_sylius_elasticsearch_plugin.query_builder.attributes');

    $services->set('bitbag_sylius_elasticsearch_plugin.query_builder.has_price_between', HasPriceBetweenQueryBuilder::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.property_name_resolver.price'),
            service('bitbag_sylius_elasticsearch_plugin.property_name_resolver.channel_pricing'),
            service('sylius.context.channel'),
            service('sylius.context.currency.channel_aware'),
            service('sylius.converter.currency'),
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.query_builder.product_attributes_by_taxon', ProductAttributesByTaxonQueryBuilder::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.query_builder.has_attribute_taxon'),
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.query_builder.product_options_by_taxon', ProductOptionsByTaxonQueryBuilder::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.query_builder.has_option_taxon'),
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.query_builder.products_by_partial_name', ProductsByPartialNameQueryBuilder::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.query_builder.contains_name'),
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.query_builder.taxon_products', TaxonProductsQueryBuilder::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.query_builder.is_enabled'),
            service('bitbag_sylius_elasticsearch_plugin.query_builder.has_channel'),
            service('bitbag_sylius_elasticsearch_plugin.query_builder.contains_name'),
            service('bitbag_sylius_elasticsearch_plugin.query_builder.has_product_taxon'),
            service('bitbag_sylius_elasticsearch_plugin.query_builder.has_options'),
            service('bitbag_sylius_elasticsearch_plugin.query_builder.has_attributes'),
            service('bitbag_sylius_elasticsearch_plugin.query_builder.has_price_between'),
            '%bitbag_es_shop_option_property_prefix%',
            '%bitbag_es_shop_attribute_property_prefix%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.query_builder.site_wide_products', SiteWideProductsQueryBuilder::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.query_builder.is_enabled'),
            service('bitbag_sylius_elasticsearch_plugin.query_builder.has_channel'),
            service('bitbag_sylius_elasticsearch_plugin.query_builder.contains_name'),
            '%bitbag_es_fuzziness%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.query_builder.taxon_facets', TaxonFacetsQueryBuilder::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.controller.request_data_handler.shop_product_list'),
            service('bitbag_sylius_elasticsearch_plugin.query_builder.taxon_products'),
            service('bitbag_sylius_elasticsearch_plugin.facet.registry'),
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.query_builder.site_wide_facets', SiteWideFacetsQueryBuilder::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.query_builder.site_wide_products'),
            service('bitbag_sylius_elasticsearch_plugin.facet.registry'),
        ]);
};

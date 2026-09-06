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

use BitBag\SyliusElasticsearchPlugin\Form\Resolver\FacetsResolver;
use BitBag\SyliusElasticsearchPlugin\Form\Resolver\ProductsFilterFacetResolver;
use BitBag\SyliusElasticsearchPlugin\Form\Type\ChoiceMapper\AttributesMapper\AttributesTypeDateMapper;
use BitBag\SyliusElasticsearchPlugin\Form\Type\ChoiceMapper\AttributesMapper\AttributesTypeDateTimeMapper;
use BitBag\SyliusElasticsearchPlugin\Form\Type\ChoiceMapper\AttributesMapper\AttributesTypePercentMapper;
use BitBag\SyliusElasticsearchPlugin\Form\Type\ChoiceMapper\ProductAttributesMapper;
use BitBag\SyliusElasticsearchPlugin\Form\Type\ChoiceMapper\ProductOptionsMapper;
use BitBag\SyliusElasticsearchPlugin\Form\Type\NameFilterType;
use BitBag\SyliusElasticsearchPlugin\Form\Type\PriceFilterType;
use BitBag\SyliusElasticsearchPlugin\Form\Type\ProductAttributesFilterType;
use BitBag\SyliusElasticsearchPlugin\Form\Type\ProductOptionsFilterType;
use BitBag\SyliusElasticsearchPlugin\Form\Type\SearchFacetsType;
use BitBag\SyliusElasticsearchPlugin\Form\Type\SearchType;
use BitBag\SyliusElasticsearchPlugin\Form\Type\ShopProductsFilterType;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('bitbag_sylius_elasticsearch_plugin.form.type.name_filter', NameFilterType::class)
        ->tag('form.type');

    $services->set('bitbag_sylius_elasticsearch_plugin.form.type.product_options_filter', ProductOptionsFilterType::class)
        ->args([
            service('bitbag.sylius_elasticsearch_plugin.context.product_options'),
            service('bitbag_sylius_elasticsearch_plugin.property_name_resolver.option'),
            service('bitbag_sylius_elasticsearch_plugin.form.type.choice_mapper.product_options'),
        ])
        ->tag('form.type');

    $services->set('bitbag_sylius_elasticsearch_plugin.form.type.product_attributes_filter', ProductAttributesFilterType::class)
        ->args([
            service('bitbag.sylius_elasticsearch_plugin.context.product_attributes'),
            service('bitbag_sylius_elasticsearch_plugin.property_name_resolver.attribute'),
            service('bitbag_sylius_elasticsearch_plugin.form.type.choice_mapper.product_attributes'),
            '%bitbag_es_excluded_filter_attributes%',
        ])
        ->tag('form.type');

    $services->set('bitbag_sylius_elasticsearch_plugin.form.type.price_filter', PriceFilterType::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.property_name_resolver.price'),
            service('sylius.context.currency.channel_aware'),
        ])
        ->tag('form.type');

    $services->set('bitbag_sylius_elasticsearch_plugin.form.type.shop_products_filter', ShopProductsFilterType::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.facet.auto_registry'),
            '%bitbag_es_shop_name_property_prefix%',
            service('bitbag_sylius_elasticsearch_plugin.form.resolver.facet_resolver'),
        ])
        ->tag('form.type');

    $services->set('bitbag_sylius_elasticsearch_plugin.form.type.choice_mapper.product_options', ProductOptionsMapper::class)
        ->args([
            service('bitbag.sylius_elasticsearch_plugin.string_formatter'),
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.form.type.choice_mapper.product_attributes', ProductAttributesMapper::class)
        ->args([
            service('bitbag.sylius_elasticsearch_plugin.repository.product_attribute_value_repository'),
            service('sylius.context.locale'),
            service('bitbag.sylius_elasticsearch_plugin.string_formatter'),
            service('bitbag.sylius_elasticsearch_plugin.context.taxon'),
            tagged_iterator('bitbag_sylius_elasticsearch_plugin.form.type.product_attributes_mapper'),
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.form.type.search', SearchType::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.facet.auto_registry'),
            service('bitbag_sylius_elasticsearch_plugin.form.resolver.site_wide_facet_resolver'),
        ])
        ->tag('form.type');

    $services->set('bitbag_sylius_elasticsearch_plugin.form.type.search.facets', SearchFacetsType::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.facet.registry'),
        ])
        ->tag('form.type');

    $services->set('bitbag_sylius_elasticsearch_plugin.form.mapper.type.date', AttributesTypeDateMapper::class)
        ->tag('bitbag_sylius_elasticsearch_plugin.form.type.product_attributes_mapper');

    $services->set('bitbag_sylius_elasticsearch_plugin.form.mapper.type.date_time', AttributesTypeDateTimeMapper::class)
        ->tag('bitbag_sylius_elasticsearch_plugin.form.type.product_attributes_mapper');

    $services->set('bitbag_sylius_elasticsearch_plugin.form.mapper.type.percent', AttributesTypePercentMapper::class)
        ->tag('bitbag_sylius_elasticsearch_plugin.form.type.product_attributes_mapper');

    $services->set('bitbag_sylius_elasticsearch_plugin.form.resolver.facet_resolver', ProductsFilterFacetResolver::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.query_builder.taxon_facets'),
            service('bitbag_sylius_elasticsearch_plugin.facet.registry'),
            service('fos_elastica.finder.bitbag_shop_product'),
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.form.resolver.site_wide_facet_resolver', FacetsResolver::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.query_builder.site_wide_facets'),
            service('bitbag_sylius_elasticsearch_plugin.facet.registry'),
            service('fos_elastica.finder.bitbag_shop_product'),
        ]);
};

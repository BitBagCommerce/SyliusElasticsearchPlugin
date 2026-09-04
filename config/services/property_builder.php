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

use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\AttributeBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\AttributeTaxonsBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\ChannelPricingBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\ChannelsBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\Mapper\ProductTaxonsMapper;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\OptionBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\OptionTaxonsBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\ProductCodeBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\ProductCreatedAtPropertyBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\ProductDescriptionBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\ProductNameBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\ProductShortDescriptionBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\ProductTaxonPositionPropertyBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\ProductTaxonsBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\SoldUnitsPropertyBuilder;

return static function (ContainerConfigurator $container): void {
    $parameters = $container->parameters();

    $parameters->set('bitbag_es_excluded_filter_options', []);
    $parameters->set('bitbag_es_excluded_filter_attributes', []);
    $parameters->set('bitbag_es_filter_attributes_max', 20);
    $parameters->set('bitbag_es_filter_options_max', 20);

    $services = $container->services();

    $services->set('bitbag_sylius_elasticsearch_plugin.property_builder.product_name', ProductNameBuilder::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.property_name_resolver.name'),
        ])
        ->tag('kernel.event_subscriber');

    $services->set('bitbag_sylius_elasticsearch_plugin.property_builder.product_code', ProductCodeBuilder::class)
        ->tag('kernel.event_subscriber');

    $services->set('bitbag_sylius_elasticsearch_plugin.property_builder.option', OptionBuilder::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.property_name_resolver.option'),
            service('bitbag.sylius_elasticsearch_plugin.string_formatter'),
        ])
        ->tag('kernel.event_subscriber');

    $services->set('bitbag_sylius_elasticsearch_plugin.property_builder.attribute', AttributeBuilder::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.property_name_resolver.attribute'),
            service('bitbag.sylius_elasticsearch_plugin.string_formatter'),
            service('sylius.context.locale'),
        ])
        ->tag('kernel.event_subscriber');

    $services->set('bitbag_sylius_elasticsearch_plugin.property_builder.product_taxons', ProductTaxonsBuilder::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.property_builder.mapper.product_taxons'),
            '%bitbag_es_shop_product_taxons_property%',
        ])
        ->tag('kernel.event_subscriber');

    $services->set('bitbag_sylius_elasticsearch_plugin.property_builder.channels', ChannelsBuilder::class)
        ->args([
            '%bitbag_es_shop_channels_property%',
        ])
        ->tag('kernel.event_subscriber');

    $services->set('bitbag_sylius_elasticsearch_plugin.property_builder.channel_pricing', ChannelPricingBuilder::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.property_name_resolver.channel_pricing'),
        ])
        ->tag('kernel.event_subscriber');

    $services->set('bitbag_sylius_elasticsearch_plugin.property_builder.sold_units', SoldUnitsPropertyBuilder::class)
        ->args([
            service('bitbag.sylius_elasticsearch_plugin.repository.order_item_repository'),
            '%bitbag_es_shop_product_sold_units%',
        ])
        ->tag('kernel.event_subscriber');

    $services->set('bitbag_sylius_elasticsearch_plugin.property_builder.product_created_at', ProductCreatedAtPropertyBuilder::class)
        ->args([
            '%bitbag_es_shop_product_created_at%',
        ])
        ->tag('kernel.event_subscriber');

    $services->set('bitbag_sylius_elasticsearch_plugin.property_builder.option_taxons', OptionTaxonsBuilder::class)
        ->args([
            service('sylius.repository.product_option_value'),
            service('bitbag.sylius_elasticsearch_plugin.repository.product_variant'),
            service('bitbag_sylius_elasticsearch_plugin.property_builder.mapper.product_taxons'),
            '%bitbag_es_shop_option_taxons_property%',
            '%bitbag_es_excluded_filter_options%',
        ])
        ->tag('kernel.event_subscriber');

    $services->set('bitbag_sylius_elasticsearch_plugin.property_builder.attribute_taxons', AttributeTaxonsBuilder::class)
        ->args([
            service('bitbag.sylius_elasticsearch_plugin.repository.taxon_repository'),
            '%bitbag_es_shop_attribute_taxons_property%',
            '%sylius_shop.product_grid.include_all_descendants%',
            '%bitbag_es_excluded_filter_attributes%',
        ])
        ->tag('kernel.event_subscriber');

    $services->set('bitbag_sylius_elasticsearch_plugin.property_builder.main_taxon_position', ProductTaxonPositionPropertyBuilder::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.property_name_resolver.taxon_position'),
        ])
        ->tag('kernel.event_subscriber');

    $services->set('bitbag_sylius_elasticsearch_plugin.property_builder.mapper.product_taxons', ProductTaxonsMapper::class)
        ->args([
            '%sylius_shop.product_grid.include_all_descendants%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.property_builder.product_description', ProductDescriptionBuilder::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.property_name_resolver.description'),
        ])
        ->tag('kernel.event_subscriber');

    $services->set('bitbag_sylius_elasticsearch_plugin.property_builder.product_short_description', ProductShortDescriptionBuilder::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.property_name_resolver.short_description'),
        ])
        ->tag('kernel.event_subscriber');
};

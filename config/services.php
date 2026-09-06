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

use BitBag\SyliusElasticsearchPlugin\Api\OpenApi\Documentation\ProductSearchDocumentationModifier;
use BitBag\SyliusElasticsearchPlugin\Formatter\StringFormatter;
use BitBag\SyliusElasticsearchPlugin\Refresher\ResourceRefresher;
use BitBag\SyliusElasticsearchPlugin\Repository\OrderItemRepository;
use BitBag\SyliusElasticsearchPlugin\Repository\ProductAttributeRepository;
use BitBag\SyliusElasticsearchPlugin\Repository\ProductAttributeValueRepository;
use BitBag\SyliusElasticsearchPlugin\Repository\ProductOptionRepository;
use BitBag\SyliusElasticsearchPlugin\Repository\ProductVariantRepository;
use BitBag\SyliusElasticsearchPlugin\Repository\TaxonRepository;

return static function (ContainerConfigurator $container): void {
    $container->import('services/**/*.php');

    $services = $container->services();

    $services->set('bitbag.sylius_elasticsearch_plugin.string_formatter', StringFormatter::class);

    $services->set('bitbag.sylius_elasticsearch_plugin.repository.product_variant', ProductVariantRepository::class)
        ->args([
            service('sylius.repository.product_variant'),
        ]);

    $services->set('bitbag.sylius_elasticsearch_plugin.repository.taxon_repository', TaxonRepository::class)
        ->args([
            service('sylius.repository.taxon'),
            service('sylius.repository.product'),
            '%sylius.model.product_taxon.class%',
            '%sylius.model.product_attribute_value.class%',
        ]);

    $services->set('bitbag.sylius_elasticsearch_plugin.repository.product_attribute_value_repository', ProductAttributeValueRepository::class)
        ->args([
            service('sylius.repository.product_attribute_value'),
            '%sylius_shop.product_grid.include_all_descendants%',
        ]);

    $services->set('bitbag.sylius_elasticsearch_plugin.repository.product_attribute_repository', ProductAttributeRepository::class)
        ->args([
            service('sylius.repository.product_attribute'),
        ]);

    $services->set('bitbag.sylius_elasticsearch_plugin.repository.order_item_repository', OrderItemRepository::class)
        ->args([
            service('sylius.repository.order_item'),
        ]);

    $services->set('bitbag.sylius_elasticsearch_plugin.refresher.resource', ResourceRefresher::class)
        ->args([
            service('service_container'),
        ]);

    $services->set('bitbag.sylius_elasticsearch_plugin.repository.product_option_repository', ProductOptionRepository::class)
        ->args([
            service('sylius.repository.product_option'),
        ]);

    $services->set(ProductSearchDocumentationModifier::class)
        ->decorate('api_platform.openapi.factory')
        ->public()
        ->args([
            service('.inner'),
        ]);
};

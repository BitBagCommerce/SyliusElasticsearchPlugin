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

use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\PaginationDataHandler;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\ShopProductListDataHandler;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\ShopProductsSortDataHandler;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\SiteWideDataHandler;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\TaxonDataHandler;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('bitbag_sylius_elasticsearch_plugin.controller.request_data_handler.shop_product_list', ShopProductListDataHandler::class)
        ->args([
            service('bitbag.sylius_elasticsearch_plugin.context.taxon'),
            service('bitbag_sylius_elasticsearch_plugin.finder.product_attributes'),
            '%bitbag_es_shop_name_property_prefix%',
            '%bitbag_es_shop_product_taxons_property%',
            '%bitbag_es_shop_option_property_prefix%',
            '%bitbag_es_shop_attribute_property_prefix%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.controller.request_data_handler.pagination', PaginationDataHandler::class)
        ->args([
            '%bitbag_es_pagination_default_limit%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.controller.request_data_handler.shop_products_sort', ShopProductsSortDataHandler::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.property_name_resolver.channel_pricing'),
            service('sylius.context.channel'),
            '%bitbag_es_shop_product_sold_units%',
            '%bitbag_es_shop_product_created_at%',
            '%bitbag_es_shop_product_price_property_prefix%',
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.controller.request_data_handler.site_wide', SiteWideDataHandler::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.controller.request_data_handler.shop_products_sort'),
            service('bitbag_sylius_elasticsearch_plugin.controller.request_data_handler.pagination'),
        ]);

    $services->set('bitbag_sylius_elasticsearch_plugin.controller.request_data_handler.taxon', TaxonDataHandler::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.controller.request_data_handler.shop_product_list'),
            service('bitbag_sylius_elasticsearch_plugin.controller.request_data_handler.shop_products_sort'),
            service('bitbag_sylius_elasticsearch_plugin.controller.request_data_handler.pagination'),
        ]);
};

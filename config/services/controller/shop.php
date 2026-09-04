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

use BitBag\SyliusElasticsearchPlugin\Controller\Action\Shop\SiteWideProductsSearchAction;
use BitBag\SyliusElasticsearchPlugin\Controller\Action\Shop\TaxonProductsSearchAction;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('bitbag_sylius_elasticsearch_plugin.controller.action.shop.taxon_products_search', TaxonProductsSearchAction::class)
        ->args([
            service('form.factory'),
            service('bitbag_sylius_elasticsearch_plugin.controller.request_data_handler.taxon'),
            service('bitbag_sylius_elasticsearch_plugin.finder.shop_products'),
            service('twig'),
        ])
        ->tag('controller.service_arguments');

    $services->set('bitbag_sylius_elasticsearch_plugin.controller.action.shop.site_wide_products_search', SiteWideProductsSearchAction::class)
        ->args([
            service('form.factory'),
            service('bitbag_sylius_elasticsearch_plugin.controller.request_data_handler.site_wide'),
            service('bitbag_sylius_elasticsearch_plugin.finder.search_products'),
            service('twig'),
        ])
        ->tag('controller.service_arguments');
};

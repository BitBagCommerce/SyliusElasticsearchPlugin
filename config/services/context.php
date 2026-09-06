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

use BitBag\SyliusElasticsearchPlugin\Context\ProductAttributesContext;
use BitBag\SyliusElasticsearchPlugin\Context\ProductOptionsContext;
use BitBag\SyliusElasticsearchPlugin\Context\TaxonContext;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('bitbag.sylius_elasticsearch_plugin.context.taxon', TaxonContext::class)
        ->args([
            service('request_stack'),
            service('sylius.repository.taxon'),
            service('sylius.context.locale'),
        ]);

    $services->set('bitbag.sylius_elasticsearch_plugin.context.product_options', ProductOptionsContext::class)
        ->args([
            service('bitbag.sylius_elasticsearch_plugin.context.taxon'),
            service('bitbag_sylius_elasticsearch_plugin.finder.product_options'),
        ]);

    $services->set('bitbag.sylius_elasticsearch_plugin.context.product_attributes', ProductAttributesContext::class)
        ->args([
            service('bitbag.sylius_elasticsearch_plugin.context.taxon'),
            service('bitbag_sylius_elasticsearch_plugin.finder.product_attributes'),
        ]);
};

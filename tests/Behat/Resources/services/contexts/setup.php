<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Tests\BitBag\SyliusElasticsearchPlugin\Behat\Context\Setup\ElasticsearchContext;
use Tests\BitBag\SyliusElasticsearchPlugin\Behat\Context\Setup\ProductAttributeContext;
use Tests\BitBag\SyliusElasticsearchPlugin\Behat\Context\Setup\ProductContext;
use Tests\BitBag\SyliusElasticsearchPlugin\Behat\Context\Setup\ProductTaxonContext;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->public();

    $services->set('bitbag.sylius_elasticsearch_plugin.behat.context.setup.product', ProductContext::class)
        ->args([
            service('sylius.behat.shared_storage'),
            service('sylius.repository.product'),
            service('sylius.factory.product'),
            service('sylius.factory.channel_pricing'),
            service('sylius.factory.product_option'),
            service('sylius.factory.product_option_value'),
            service('doctrine.orm.entity_manager'),
            service('sylius.resolver.product_variant.default'),
            service('sylius.generator.slug'),
        ]);

    $services->set('bitbag.sylius_elasticsearch_plugin.behat.context.setup.product_attribute', ProductAttributeContext::class)
        ->args([
            service('sylius.behat.shared_storage'),
            service('sylius.repository.product_attribute'),
            service('sylius.factory.product_attribute'),
            service('sylius.factory.product_attribute_value'),
            service('doctrine.orm.entity_manager'),
        ]);

    $services->set('bitbag.sylius_elasticsearch_plugin.behat.context.setup.product_taxon', ProductTaxonContext::class)
        ->args([
            service('sylius.behat.shared_storage'),
            service('sylius.factory.product_taxon'),
            service('doctrine.orm.entity_manager'),
        ]);

    $services->set('bitbag.sylius_elasticsearch_plugin.behat.context.setup.elasticsearch', ElasticsearchContext::class)
        ->args([
            service('bitbag.sylius_elasticsearch_plugin.behat.populate'),
        ]);
};

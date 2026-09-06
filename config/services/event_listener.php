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

use BitBag\SyliusElasticsearchPlugin\EventListener\OrderProductsListener;
use BitBag\SyliusElasticsearchPlugin\EventListener\ProductTaxonIndexListener;
use BitBag\SyliusElasticsearchPlugin\EventListener\ResourceIndexListener;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('bitbag_sylius_elasticsearch_plugin.event_listener.resource_index', ResourceIndexListener::class)
        ->args([
            service('bitbag.sylius_elasticsearch_plugin.refresher.resource'),
            [
                [
                    'model' => '%sylius.model.product_attribute.class%',
                    'serviceId' => service('fos_elastica.object_persister.bitbag_attribute_taxons'),
                ],
                [
                    'model' => '%sylius.model.product_option.class%',
                    'serviceId' => service('fos_elastica.object_persister.bitbag_option_taxons'),
                ],
                [
                    'getParentMethod' => 'getProduct',
                    'model' => '%sylius.model.product.class%',
                    'serviceId' => service('fos_elastica.object_persister.bitbag_shop_product'),
                ],
            ],
            service('sylius.repository.product_attribute'),
            service('sylius.repository.product_option'),
        ])
        ->tag('kernel.event_listener', ['event' => 'sylius.product_attribute.post_create', 'method' => 'updateIndex'])
        ->tag('kernel.event_listener', ['event' => 'sylius.product_attribute.post_update', 'method' => 'updateIndex'])
        ->tag('kernel.event_listener', ['event' => 'sylius.product_attribute.post_delete', 'method' => 'updateIndex'])
        ->tag('kernel.event_listener', ['event' => 'sylius.option.post_create', 'method' => 'updateIndex'])
        ->tag('kernel.event_listener', ['event' => 'sylius.option.post_update', 'method' => 'updateIndex'])
        ->tag('kernel.event_listener', ['event' => 'sylius.option.post_delete', 'method' => 'updateIndex'])
        ->tag('kernel.event_listener', ['event' => 'sylius.product.post_create', 'method' => 'updateIndex'])
        ->tag('kernel.event_listener', ['event' => 'sylius.product.post_update', 'method' => 'updateIndex'])
        ->tag('kernel.event_listener', ['event' => 'sylius.product.post_delete', 'method' => 'updateIndex'])
        ->tag('kernel.event_listener', ['event' => 'sylius.product_variant.post_create', 'method' => 'updateIndex'])
        ->tag('kernel.event_listener', ['event' => 'sylius.product_variant.post_update', 'method' => 'updateIndex'])
        ->tag('kernel.event_listener', ['event' => 'sylius.product_variant.post_delete', 'method' => 'updateIndex']);

    $services->set('bitbag_sylius_elasticsearch_plugin.event_listener.product_taxon_index', ProductTaxonIndexListener::class)
        ->args([
            service('bitbag.sylius_elasticsearch_plugin.refresher.resource'),
            service('fos_elastica.object_persister.bitbag_shop_product'),
        ])
        ->tag('doctrine.orm.entity_listener', ['event' => 'postUpdate', 'method' => 'updateIndex', 'entity' => '%sylius.model.product_taxon.class%']);

    $services->set('bitbag_sylius_elasticsearch_plugin.event_listener.order_products', OrderProductsListener::class)
        ->public()
        ->args([
            service('bitbag.sylius_elasticsearch_plugin.refresher.resource'),
            service('fos_elastica.object_persister.bitbag_shop_product'),
        ])
        ->tag('kernel.event_listener', ['event' => 'sylius.order.post_complete', 'method' => 'updateOrderProducts']);
};

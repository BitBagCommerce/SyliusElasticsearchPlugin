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

use BitBag\SyliusElasticsearchPlugin\Controller\Action\Api\ListProductsByPartialNameAction;
use BitBag\SyliusElasticsearchPlugin\Transformer\Product\ChannelPricingTransformer;
use BitBag\SyliusElasticsearchPlugin\Transformer\Product\ImageTransformer;
use BitBag\SyliusElasticsearchPlugin\Transformer\Product\SlugTransformer;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('bitbag_sylius_elasticsearch_plugin.controller.action.shop.auto_complete_product_name', ListProductsByPartialNameAction::class)
        ->args([
            service('bitbag_sylius_elasticsearch_plugin.finder.named_products'),
            service(SlugTransformer::class),
            service(ChannelPricingTransformer::class),
            service(ImageTransformer::class),
        ])
        ->tag('controller.service_arguments');
};

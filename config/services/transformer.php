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

use BitBag\SyliusElasticsearchPlugin\Transformer\Product\ChannelPricingTransformer;
use BitBag\SyliusElasticsearchPlugin\Transformer\Product\ImageTransformer;
use BitBag\SyliusElasticsearchPlugin\Transformer\Product\SlugTransformer;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set(ChannelPricingTransformer::class)
        ->args([
            service('sylius.context.channel'),
            service('sylius.context.locale'),
            service('sylius.resolver.product_variant.default'),
            service('sylius.formatter.money'),
        ]);

    $services->set(ImageTransformer::class)
        ->args([
            service('liip_imagine.service.filter'),
        ]);

    $services->set(SlugTransformer::class)
        ->args([
            service('router.default'),
        ]);
};

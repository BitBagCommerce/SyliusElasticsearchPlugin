<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Tests\BitBag\SyliusElasticsearchPlugin\Behat\Context\Api\Shop\ProductContext;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->public();

    $services->set('bitbag.sylius_elasticsearch_plugin.behat.context.api.shop.product', ProductContext::class)
        ->args([
            service('bitbag.test.client'),
            service('router.default'),
        ]);
};

<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Tests\BitBag\SyliusElasticsearchPlugin\Behat\Context\Ui\Shop\HomepageContext;
use Tests\BitBag\SyliusElasticsearchPlugin\Behat\Context\Ui\Shop\ProductContext;
use Tests\BitBag\SyliusElasticsearchPlugin\Behat\Context\Ui\Shop\SearchContext;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->public();

    $services->set('bitbag.sylius_elasticsearch_plugin.behat.context.ui.shop.product', ProductContext::class)
        ->args([
            service('bitbag.sylius_elasticsearch_plugin.behat.context.page.shop.product.index'),
            service('sylius.behat.shared_storage'),
        ]);

    $services->set('bitbag.sylius_elasticsearch_plugin.behat.context.ui.shop.search', SearchContext::class)
        ->args([
            service('bitbag.sylius_elasticsearch_plugin.behat.context.page.shop.search'),
        ]);

    $services->set('bitbag.sylius_elasticsearch_plugin.behat.context.ui.shop.home_page', HomepageContext::class)
        ->args([
            service('bitbag.sylius_elasticsearch_plugin.behat.context.page.shop.home_page'),
        ]);
};

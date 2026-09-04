<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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

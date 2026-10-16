<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Tests\BitBag\SyliusElasticsearchPlugin\Behat\Page\Shop\TaxonProductsPage;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('bitbag.sylius_elasticsearch_plugin.behat.context.page.shop.taxon_products', TaxonProductsPage::class)
        ->parent('sylius.behat.symfony_page')
        ->private()
    ;
};

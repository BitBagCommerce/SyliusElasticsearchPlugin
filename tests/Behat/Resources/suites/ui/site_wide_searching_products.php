<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

use Behat\Config\Config;
use Behat\Config\Filter\TagFilter;
use Behat\Config\Profile;
use Behat\Config\Suite;

return (new Config())
    ->withProfile(
        (new Profile('default'))
        ->withSuite(
            (new Suite('ui_site_wide_searching_products'))
            ->withContexts(
                'sylius.behat.context.hook.doctrine_orm',
                'sylius.behat.context.transform.shared_storage',
                'sylius.behat.context.transform.taxon',
                'sylius.behat.context.transform.product',
                'sylius.behat.context.transform.lexical',
                'sylius.behat.context.transform.product_option',
                'sylius.behat.context.setup.channel',
                'sylius.behat.context.setup.taxonomy',
                'sylius.behat.context.setup.product_taxon',
                'sylius.behat.context.setup.product',
                'sylius.behat.context.setup.product_attribute',
                'sylius.behat.context.setup.product_option',
                'bitbag.sylius_elasticsearch_plugin.behat.context.setup.product',
                'bitbag.sylius_elasticsearch_plugin.behat.context.setup.elasticsearch',
                'sylius.behat.context.ui.shop.product',
                'bitbag.sylius_elasticsearch_plugin.behat.context.ui.shop.search',
                'bitbag.sylius_elasticsearch_plugin.behat.context.ui.shop.home_page',
            )
            ->withFilter(new TagFilter('@site_wide_searching_products&&@ui')),
        ),
    )
;

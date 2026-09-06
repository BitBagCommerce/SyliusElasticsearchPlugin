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
            (new Suite('ui_filtering_products'))
            ->withContexts(
                'sylius.behat.context.hook.doctrine_orm',
                'sylius.behat.context.transform.channel',
                'sylius.behat.context.transform.lexical',
                'sylius.behat.context.transform.locale',
                'sylius.behat.context.transform.product',
                'sylius.behat.context.transform.shared_storage',
                'sylius.behat.context.transform.taxon',
                'sylius.behat.context.setup.channel',
                'sylius.behat.context.setup.currency',
                'sylius.behat.context.setup.locale',
                'sylius.behat.context.setup.taxonomy',
                'bitbag.sylius_elasticsearch_plugin.behat.context.setup.product',
                'bitbag.sylius_elasticsearch_plugin.behat.context.setup.product_attribute',
                'bitbag.sylius_elasticsearch_plugin.behat.context.setup.product_taxon',
                'bitbag.sylius_elasticsearch_plugin.behat.context.setup.elasticsearch',
                'bitbag.sylius_elasticsearch_plugin.behat.context.ui.shop.product',
            )
            ->withFilter(new TagFilter('@filtering_products&&@ui')),
        ),
    )
;

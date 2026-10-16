<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

$bundles = [
    FOS\ElasticaBundle\FOSElasticaBundle::class => ['all' => true],
    BitBag\SyliusElasticsearchPlugin\BitBagSyliusElasticsearchPlugin::class => ['all' => true],
];

if (class_exists(winzou\Bundle\StateMachineBundle\winzouStateMachineBundle::class)) {
    $bundles[winzou\Bundle\StateMachineBundle\winzouStateMachineBundle::class] = ['all' => true];
}

return $bundles;

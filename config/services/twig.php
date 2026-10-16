<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

use BitBag\SyliusElasticsearchPlugin\Twig\Component\SearchFormComponent;
use BitBag\SyliusElasticsearchPlugin\Twig\Extension\UnsetArrayElementsExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('bitbag.sylius_elasticsearch_plugin.twig.extension.unset_array_elements', UnsetArrayElementsExtension::class)
        ->tag('twig.extension')
    ;

    $services->set('bitbag.sylius_elasticsearch_plugin.twig.component.product_search_form', SearchFormComponent::class)
        ->args([
            service('form.factory'),
        ])
        ->tag('sylius.twig_component', ['key' => 'bitbag.sylius_elasticsearch_plugin:search_form'])
    ;
};

<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

final class BitBagSyliusElasticsearchExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $config, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.xml');

        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $config);

    }

    public function prepend(ContainerBuilder $container)
    {
       // dump("je passe dans preprend");
        $configs = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration(new Configuration(), $configs);
        $container->setParameter('bitbag_es_filter_attributes_max', $config['filter_attributes_max']);
        $container->setParameter('bitbag_es_filter_options_max', $config['filter_options_max']);
        $container->setParameter('bitbag_es_shop_name_property_prefix', $config['shop_name_property_prefix']);
        $container->setParameter('bitbag_es_excluded_filter_options', $config['excluded_filter']['options']);
        $container->setParameter('bitbag_es_excluded_filter_attributes', $config['excluded_filter']['attributes']);
        dump($configs);
        dump($config);
        //$container = prependExtensionConfig($name, $config);
    }
}

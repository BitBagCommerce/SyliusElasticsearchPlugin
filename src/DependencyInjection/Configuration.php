<?php


namespace BitBag\SyliusElasticsearchPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('bitbag_sylius_elasticsearch');

        $treeBuilder->getRootNode('bitbag_sylius_elasticsearch')
            ->children()
                ->arrayNode('excluded_filter')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('options')
                            ->scalarPrototype()->end()
                            ->defaultValue([])
                        ->end()
                        ->arrayNode('attributes')
                            ->scalarPrototype()->end()
                            ->defaultValue([])
                         ->end()
                    ->end()
                ->end()
                ->integerNode('filter_attributes_max')
                    ->defaultValue(20)
                ->end()
                ->integerNode('filter_options_max')
                    ->defaultValue(20)
                ->end()
                ->scalarNode('shop_name_property_prefix')
                    ->defaultValue('sylius')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

}
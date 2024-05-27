<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type;

use BitBag\SyliusElasticsearchPlugin\Facet\AutoDiscoverRegistryInterface;
use BitBag\SyliusElasticsearchPlugin\Form\EventSubscriber\AddFacetsEventSubscriber;
use BitBag\SyliusElasticsearchPlugin\Form\Resolver\ProductsFilterFacetResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

final class ShopProductsFilterType extends AbstractFilterType
{
    public function __construct(
        private AutoDiscoverRegistryInterface $autoDiscoverRegistry,
        private string $namePropertyPrefix,
        private ProductsFilterFacetResolverInterface $facetResolver
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add($this->namePropertyPrefix, NameFilterType::class)
            ->add('price', PriceFilterType::class, ['required' => false, 'label' => false])
            ->setMethod('GET');

        $builder->addEventSubscriber(new AddFacetsEventSubscriber(
            $this->autoDiscoverRegistry,
            $this->facetResolver,
            $this->namePropertyPrefix
        ));
    }
}

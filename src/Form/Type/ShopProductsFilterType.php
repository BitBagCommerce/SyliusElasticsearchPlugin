<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type;

use BitBag\SyliusElasticsearchPlugin\Form\Resolver\ProductsFilterFacetResolverInterface;
use FOS\ElasticaBundle\Paginator\FantaPaginatorAdapter;
use Pagerfanta\Adapter\AdapterInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ShopProductsFilterType extends AbstractFilterType
{
    private string $namePropertyPrefix;

    private ProductsFilterFacetResolverInterface $facetResolver;

    public function __construct(
        string $namePropertyPrefix,
        ProductsFilterFacetResolverInterface $facetResolver
    ) {
        $this->namePropertyPrefix = $namePropertyPrefix;
        $this->facetResolver = $facetResolver;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add($this->namePropertyPrefix, NameFilterType::class)
            ->add('options', ProductOptionsFilterType::class, ['required' => false, 'label' => false])
            ->add('attributes', ProductAttributesFilterType::class, ['required' => false, 'label' => false])
            ->add('price', PriceFilterType::class, ['required' => false, 'label' => false])
            ->setMethod('GET');

        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'addFacets']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    public function addFacets(FormEvent $event): void
    {
        $facets = $this->facetResolver->resolveFacets($event, $this->namePropertyPrefix);

        if ($facets->getAdapter()) {
            $this->modifyForm($event->getForm(), $facets->getAdapter());
        }
    }

    private function modifyForm(FormInterface $form, AdapterInterface $adapter): void
    {
        if (!$adapter instanceof FantaPaginatorAdapter) {
            return;
        }

        $form->add(
            'facets',
            SearchFacetsType::class,
            [
                'facets' => $adapter->getAggregations(),
                'label' => false,
            ]
        );
    }
}

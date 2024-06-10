<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\EventSubscriber;

use BitBag\SyliusElasticsearchPlugin\Facet\AutoDiscoverRegistryInterface;
use BitBag\SyliusElasticsearchPlugin\Form\Resolver\ProductsFilterFacetResolverInterface;
use BitBag\SyliusElasticsearchPlugin\Form\Type\SearchFacetsType;
use FOS\ElasticaBundle\Paginator\FantaPaginatorAdapter;
use Pagerfanta\Adapter\AdapterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

final class AddFacetsEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private AutoDiscoverRegistryInterface $autoDiscoverRegistry,
        private ProductsFilterFacetResolverInterface $facetsResolver,
        private string $namePropertyPrefix = ''
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'addFacets',
        ];
    }

    public function addFacets(FormEvent $event): void
    {
        $this->autoDiscoverRegistry->autoRegister();
        $adapter = $this->facetsResolver->resolveFacets($event, $this->namePropertyPrefix)->getAdapter();

        $this->modifyForm($event->getForm(), $adapter);
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

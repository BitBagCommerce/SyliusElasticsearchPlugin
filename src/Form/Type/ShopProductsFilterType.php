<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type;

use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\DataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Facet\RegistryInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Pagerfanta\Adapter\AdapterInterface;
use FOS\ElasticaBundle\Paginator\FantaPaginatorAdapter;
use Elastica\Query;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ShopProductsFilterType extends AbstractFilterType
{
    /** @var string */
    private $namePropertyPrefix;

    /** @var PaginatedFinderInterface */
    private $finder;

    /** @var RegistryInterface */
    private $facetRegistry;

    /** @var QueryBuilderInterface */
    private $searchProductsQueryBuilder;

    /** @var DataHandlerInterface */
    private $shopProductListDataHandler;

    public function __construct(
        string $namePropertyPrefix,
        PaginatedFinderInterface $finder,
        RegistryInterface $facetRegistry,
        QueryBuilderInterface $searchProductsQueryBuilder,
        DataHandlerInterface $shopProductListDataHandler
    ) {
        $this->namePropertyPrefix = $namePropertyPrefix;
        $this->finder = $finder;
        $this->facetRegistry = $facetRegistry;
        $this->searchProductsQueryBuilder = $searchProductsQueryBuilder;
        $this->shopProductListDataHandler = $shopProductListDataHandler;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add($this->namePropertyPrefix, NameFilterType::class)
            ->add('options', ProductOptionsFilterType::class, ['required' => false, 'label' => false])
            ->add('attributes', ProductAttributesFilterType::class, ['required' => false, 'label' => false])
            ->add('price', PriceFilterType::class, ['required' => false, 'label' => false])
            ->setMethod('GET');

        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'resolveFacets']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    public function resolveFacets(FormEvent $event): void
    {
        $query = $this->getQuery($event);

        foreach ($this->facetRegistry->getFacets() as $facetId => $facet) {
            $query->addAggregation($facet->getAggregation()->setName($facetId));
        }

        $query->setSize(0);

        $results = $this->finder->findPaginated($query);

        if ($results->getAdapter()) {
            $this->modifyForm($event->getForm(), $results->getAdapter());
        }
    }

    private function getQuery(FormEvent $event): Query
    {
        $eventData = $event->getData();
        if (!isset($eventData[$this->namePropertyPrefix])) {
            $eventData[$this->namePropertyPrefix] = '';
        }

        $data = $this->shopProductListDataHandler->retrieveData($eventData);

        return new Query($this->searchProductsQueryBuilder->buildQuery($data));
    }

    private function modifyForm(FormInterface $form, AdapterInterface $adapter): void
    {
        if (!$adapter instanceof FantaPaginatorAdapter) {
            return;
        }

        $form->add('facets', SearchFacetsType::class, [
                'facets' => $adapter->getAggregations(),
                'label' => false
            ]
        );
    }
}

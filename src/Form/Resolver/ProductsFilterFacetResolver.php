<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Resolver;

use BitBag\SyliusElasticsearchPlugin\Facet\RegistryInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\FormQueryBuilder\TaxonFacetsQueryBuilderInterface;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormEvent;

final class ProductsFilterFacetResolver implements ProductsFilterFacetResolverInterface
{
    public function __construct(
        private TaxonFacetsQueryBuilderInterface $queryBuilder,
        private RegistryInterface $facetRegistry,
        private PaginatedFinderInterface $finder
    ) {
    }

    public function resolveFacets(FormEvent $event, string $namePropertyPrefix): Pagerfanta
    {
        $query = $this->queryBuilder->getQuery($event, $namePropertyPrefix);

        foreach ($this->facetRegistry->getFacets() as $facetId => $facet) {
            $query->addAggregation($facet->getAggregation()->setName($facetId));
        }

        $query->setSize(0);

        return $this->finder->findPaginated($query);
    }
}

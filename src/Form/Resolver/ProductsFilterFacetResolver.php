<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
        private PaginatedFinderInterface $finder,
    ) {
    }

    public function resolveFacets(FormEvent $event, string $namePropertyPrefix): Pagerfanta
    {
        $query = $this->queryBuilder->getQuery($event, $namePropertyPrefix);

        foreach ($this->facetRegistry->getFacets() as $facetId => $facet) {
            $query->addAggregation($facet->getAggregation()->setName((string) $facetId));
        }

        $query->setSize(0);

        return $this->finder->findPaginated($query);
    }
}

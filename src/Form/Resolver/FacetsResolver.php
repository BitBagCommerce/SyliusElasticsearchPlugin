<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Resolver;

use BitBag\SyliusElasticsearchPlugin\Facet\RegistryInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\FormQueryBuilder\SiteWideFacetsQueryBuilderInterface;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormEvent;

final class FacetsResolver implements ProductsFilterFacetResolverInterface
{
    public function __construct(
        private SiteWideFacetsQueryBuilderInterface $queryBuilder,
        private RegistryInterface $facetRegistry,
        private PaginatedFinderInterface $finder
    ) {
    }

    public function resolveFacets(FormEvent $event, string $namePropertyPrefix): Pagerfanta
    {
        $query = $this->queryBuilder->getQuery($event);

        foreach ($this->facetRegistry->getFacets() as $facetId => $facet) {
            $query->addAggregation($facet->getAggregation()->setName($facetId));
        }

        $query->setSize(0);

        return $this->finder->findPaginated($query);
    }
}

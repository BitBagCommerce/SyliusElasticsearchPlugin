<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Api\Resolver;

use BitBag\SyliusElasticsearchPlugin\Facet\AutoDiscoverRegistryInterface;
use BitBag\SyliusElasticsearchPlugin\Facet\RegistryInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use FOS\ElasticaBundle\Paginator\FantaPaginatorAdapter;

final class FacetsResolver implements FacetsResolverInterface
{
    public function __construct(
        private AutoDiscoverRegistryInterface $autoDiscoverRegistry,
        private QueryBuilderInterface $queryBuilder,
        private RegistryInterface $facetRegistry,
        private PaginatedFinderInterface $finder,
        ) {
    }

    public function resolve(array $data): array
    {
        $this->autoDiscoverRegistry->autoRegister();

        /** @var Query\BoolQuery $boolQuery */
        $boolQuery = $this->queryBuilder->buildQuery($data);
        $query = new Query($boolQuery);
        $query->setSize(0);

        foreach ($this->facetRegistry->getFacets() as $facetId => $facet) {
            $query->addAggregation($facet->getAggregation()->setName($facetId));
        }

        foreach ($data['facets'] ?? [] as $facetId => $selectedBuckets) {
            if (!$selectedBuckets) {
                continue;
            }

            $facet = $this->facetRegistry->getFacetById($facetId);
            $boolQuery->addFilter($facet->getQuery($selectedBuckets));
        }

        $facets = $this->finder->findPaginated($query);
        $adapter = $facets->getAdapter();
        if (!$adapter instanceof FantaPaginatorAdapter) {
            return [];
        }

        return array_filter($adapter->getAggregations(), function ($facet) {
            return [] !== ($facet['buckets'] ?? []);
        });
    }
}

<?php

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

        $boolQuery = $this->queryBuilder->buildQuery($data);
        $query = new Query($boolQuery);
        $query->setSize(0);

        foreach ($this->facetRegistry->getFacets() as $facetId => $facet) {
            $query->addAggregation($facet->getAggregation()->setName($facetId));
        }

        $facets = $this->finder->findPaginated($query);
        $adapter = $facets->getAdapter();
        if (!$adapter instanceof FantaPaginatorAdapter) {
            return [];
        }

        return $adapter->getAggregations();
    }
}

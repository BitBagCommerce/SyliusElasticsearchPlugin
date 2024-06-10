<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder\FormQueryBuilder;

use BitBag\SyliusElasticsearchPlugin\Facet\RegistryInterface;
use BitBag\SyliusElasticsearchPlugin\Model\Search;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query;
use Symfony\Component\Form\FormEvent;

final class SiteWideFacetsQueryBuilder implements SiteWideFacetsQueryBuilderInterface
{
    public function __construct(
        private QueryBuilderInterface $queryBuilder,
        private RegistryInterface $facetRegistry,
    ) {
    }

    public function getQuery(FormEvent $event): Query
    {
        /** @var Search $data */
        $data = $event->getData();

        $boolQuery = $this->queryBuilder->buildQuery([
            'query' => $data['box']['query'] ?? '',
        ]);

        foreach ($data['facets'] ?? [] as $facetId => $selectedBuckets) {
            if (!$selectedBuckets) {
                continue;
            }

            $facet = $this->facetRegistry->getFacetById($facetId);
            $boolQuery->addFilter($facet->getQuery($selectedBuckets));
        }

        return new Query($boolQuery);
    }
}

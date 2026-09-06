<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder\FormQueryBuilder;

use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\DataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Facet\RegistryInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query;
use Symfony\Component\Form\FormEvent;

final class TaxonFacetsQueryBuilder implements TaxonFacetsQueryBuilderInterface
{
    public function __construct(
        private DataHandlerInterface $shopProductListDataHandler,
        private QueryBuilderInterface $searchProductsQueryBuilder,
        private RegistryInterface $facetRegistry,
    ) {
    }

    public function getQuery(FormEvent $event, string $namePropertyPrefix): Query
    {
        $eventData = $event->getData();
        if (!isset($eventData[$namePropertyPrefix])) {
            $eventData[$namePropertyPrefix] = '';
        }

        $data = $this->shopProductListDataHandler->retrieveData($eventData);

        /** @var Query\BoolQuery $boolQuery */
        $boolQuery = $this->searchProductsQueryBuilder->buildQuery($data);

        foreach ($data['facets'] ?? [] as $facetId => $selectedBuckets) {
            if (!$selectedBuckets) {
                continue;
            }

            $facet = $this->facetRegistry->getFacetById((string) $facetId);
            $boolQuery->addFilter($facet->getQuery($selectedBuckets));
        }

        return new Query($boolQuery);
    }
}

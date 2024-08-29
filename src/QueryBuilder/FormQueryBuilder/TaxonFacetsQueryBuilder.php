<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
        private RegistryInterface $facetRegistry
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

            $facet = $this->facetRegistry->getFacetById($facetId);
            $boolQuery->addFilter($facet->getQuery($selectedBuckets));
        }

        return new Query($boolQuery);
    }
}

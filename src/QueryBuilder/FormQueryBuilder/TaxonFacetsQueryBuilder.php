<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder\FormQueryBuilder;

use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\DataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query;
use Symfony\Component\Form\FormEvent;

final class TaxonFacetsQueryBuilder implements TaxonFacetsQueryBuilderInterface
{
    private DataHandlerInterface $shopProductListDataHandler;

    private QueryBuilderInterface $searchProductsQueryBuilder;

    public function __construct(
        DataHandlerInterface $shopProductListDataHandler,
        QueryBuilderInterface $searchProductsQueryBuilder
    ) {
        $this->shopProductListDataHandler = $shopProductListDataHandler;
        $this->searchProductsQueryBuilder = $searchProductsQueryBuilder;
    }

    public function getQuery(FormEvent $event, string $namePropertyPrefix): Query
    {
        $eventData = $event->getData();
        if (!isset($eventData[$namePropertyPrefix])) {
            $eventData[$namePropertyPrefix] = '';
        }

        $data = $this->shopProductListDataHandler->retrieveData($eventData);

        return new Query($this->searchProductsQueryBuilder->buildQuery($data));
    }
}

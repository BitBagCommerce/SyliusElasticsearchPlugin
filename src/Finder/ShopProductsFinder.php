<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);
namespace BitBag\SyliusElasticsearchPlugin\Finder;

use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\PaginationDataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\SortDataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Notifier\BoolQueryDispatcherInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Pagerfanta\Pagerfanta;

final class ShopProductsFinder implements ShopProductsFinderInterface
{
    /** @var QueryBuilderInterface */
    private $shopProductsQueryBuilder;

    /** @var PaginatedFinderInterface */
    private $productFinder;

    /**
     * @var BoolQueryDispatcherInterface
     */
    private $boolQueryDispatcher;

    public function __construct(
        QueryBuilderInterface $shopProductsQueryBuilder,
        PaginatedFinderInterface $productFinder,
        BoolQueryDispatcherInterface $boolQueryDispatcher
    ) {
        $this->shopProductsQueryBuilder = $shopProductsQueryBuilder;
        $this->productFinder = $productFinder;
        $this->boolQueryDispatcher = $boolQueryDispatcher;
    }

    public function find(array $data): Pagerfanta
    {
        /** @var Query\BoolQuery $boolQuery */
        $boolQuery = $this->shopProductsQueryBuilder->buildQuery($data);

        $query = new Query($boolQuery);


        $this->boolQueryDispatcher->dispatchNewQuery($boolQuery);


        $query->addSort($data[SortDataHandlerInterface::SORT_INDEX]);

        $products = $this->productFinder->findPaginated($query);
        $products->setMaxPerPage($data[PaginationDataHandlerInterface::LIMIT_INDEX]);
        $products->setCurrentPage($data[PaginationDataHandlerInterface::PAGE_INDEX]);

        return $products;
    }
}

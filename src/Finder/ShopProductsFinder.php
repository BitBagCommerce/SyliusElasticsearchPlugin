<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Finder;

use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\PaginationDataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\SortDataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Pagerfanta\Pagerfanta;

final class ShopProductsFinder implements ShopProductsFinderInterface
{
    private QueryBuilderInterface $shopProductsQueryBuilder;

    private PaginatedFinderInterface $productFinder;

    public function __construct(
        QueryBuilderInterface $shopProductsQueryBuilder,
        PaginatedFinderInterface $productFinder
    ) {
        $this->shopProductsQueryBuilder = $shopProductsQueryBuilder;
        $this->productFinder = $productFinder;
    }

    public function find(array $data): Pagerfanta
    {
        $boolQuery = $this->shopProductsQueryBuilder->buildQuery($data);

        $query = new Query($boolQuery);
        $query->addSort($data[SortDataHandlerInterface::SORT_INDEX]);

        $products = $this->productFinder->findPaginated($query);
        if (null !== $data[PaginationDataHandlerInterface::LIMIT_INDEX]) {
            $products->setMaxPerPage($data[PaginationDataHandlerInterface::LIMIT_INDEX]);
        }
        if (null !== $data[PaginationDataHandlerInterface::PAGE_INDEX]) {
            $products->setCurrentPage($data[PaginationDataHandlerInterface::PAGE_INDEX]);
        }

        return $products;
    }
}

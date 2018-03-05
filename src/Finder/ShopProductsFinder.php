<?php

/*
 * This file has been created by developers from BitBag. 
 * Feel free to contact us once you face any issues or want to start
 * another great project. 
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl. 
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Finder;

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Pagerfanta\Pagerfanta;

final class ShopProductsFinder implements FinderInterface
{
    /**
     * @var QueryBuilderInterface
     */
    private $shopProductsQueryBuilder;

    /**
     * @var PaginatedFinderInterface
     */
    private $productFinder;

    /**
     * @param QueryBuilderInterface $shopProductsQueryBuilder
     * @param PaginatedFinderInterface $productFinder
     */
    public function __construct(
        QueryBuilderInterface $shopProductsQueryBuilder,
        PaginatedFinderInterface $productFinder
    )
    {
        $this->shopProductsQueryBuilder = $shopProductsQueryBuilder;
        $this->productFinder = $productFinder;
    }

    /**
     * {@inheritdoc}
     */
    public function find(array $data)
    {
        $query = $this->shopProductsQueryBuilder->buildQuery($data);
        $result = $this->productFinder->findPaginated($query);

        $result->setCurrentPage($data['page']);

        return $result;
    }
}

<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Finder;

use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\PaginationDataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\SortDataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Finder\ShopProductsFinder;
use BitBag\SyliusElasticsearchPlugin\Finder\ShopProductsFinderInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query\AbstractQuery;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Pagerfanta\Pagerfanta;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

final class ShopProductsFinderSpec extends ObjectBehavior
{
    function let(
        QueryBuilderInterface $shopProductsQueryBuilder,
        PaginatedFinderInterface $productFinder
    ): void {
        $this->beConstructedWith(
            $shopProductsQueryBuilder,
            $productFinder
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ShopProductsFinder::class);
    }

    function it_implements_shop_products_finder_interface(): void
    {
        $this->shouldHaveType(ShopProductsFinderInterface::class);
    }

    function it_finds(
        QueryBuilderInterface $shopProductsQueryBuilder,
        PaginatedFinderInterface $productFinder,
        AbstractQuery $boolQuery,
        Pagerfanta $pagerfanta
    ): void {
        $data = [
            SortDataHandlerInterface::SORT_INDEX => null,
            PaginationDataHandlerInterface::PAGE_INDEX => null,
            PaginationDataHandlerInterface::LIMIT_INDEX => null,
        ];

        $shopProductsQueryBuilder->buildQuery($data)->willReturn($boolQuery);

        $productFinder->findPaginated(Argument::any())->willReturn($pagerfanta);

        $this->find($data)->shouldBeEqualTo($pagerfanta);
    }
}

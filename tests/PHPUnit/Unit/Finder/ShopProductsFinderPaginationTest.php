<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Unit\Finder;

use BitBag\SyliusElasticsearchPlugin\Facet\RegistryInterface;
use BitBag\SyliusElasticsearchPlugin\Finder\ShopProductsFinder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query\BoolQuery;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ShopProductsFinderPaginationTest extends TestCase
{
    public function test_pages_beyond_the_product_count_are_not_found(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->finder(range(1, 5))->find(['page' => 2, 'limit' => 9]);
    }

    public function test_valid_second_pages_keep_the_expected_results(): void
    {
        $products = $this->finder(range(1, 20))->find(['page' => 2, 'limit' => 9]);

        self::assertSame(2, $products->getCurrentPage());
        self::assertSame(range(10, 18), $products->getCurrentPageResults());
    }

    public function test_an_empty_first_page_remains_valid(): void
    {
        $products = $this->finder([])->find(['page' => 1, 'limit' => 9]);

        self::assertSame(0, $products->getNbResults());
        self::assertSame([], $products->getCurrentPageResults());
    }

    private function finder(array $products): ShopProductsFinder
    {
        $builder = $this->createMock(QueryBuilderInterface::class);
        $builder->method('buildQuery')->willReturn(new BoolQuery());
        $finder = $this->createMock(PaginatedFinderInterface::class);
        $finder->method('findPaginated')->willReturn(new Pagerfanta(new ArrayAdapter($products)));

        return new ShopProductsFinder($builder, $finder, $this->createMock(RegistryInterface::class));
    }
}

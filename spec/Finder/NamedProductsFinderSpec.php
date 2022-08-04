<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Finder;

use BitBag\SyliusElasticsearchPlugin\Finder\NamedProductsFinderInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query\AbstractQuery;
use FOS\ElasticaBundle\Finder\FinderInterface;
use PhpSpec\ObjectBehavior;

final class NamedProductsFinderSpec extends ObjectBehavior
{
    public function let(
        QueryBuilderInterface $productsByPartialNameQueryBuilder,
        FinderInterface $productsFinder
    ): void {
        $this->beConstructedWith($productsByPartialNameQueryBuilder, $productsFinder);
    }

    public function it_is_a_named_products_finder(): void
    {
        $this->shouldImplement(NamedProductsFinderInterface::class);
    }

    public function it_finds_by_partial_name_of_products(
        QueryBuilderInterface $productsByPartialNameQueryBuilder,
        FinderInterface $productsFinder,
        AbstractQuery $query
    ): void {
        $productsByPartialNameQueryBuilder->buildQuery(['name' => 'part'])->willReturn($query);

        $productsFinder->find($query)->willReturn([]);

        $this->findByNamePart('part')->shouldBeArray();
    }
}

<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use PhpSpec\ObjectBehavior;

final class ProductsByPartialNameQueryBuilderSpec extends ObjectBehavior
{
    function let(QueryBuilderInterface $containsNameQueryBuilder): void
    {
        $this->beConstructedWith($containsNameQueryBuilder);
    }

    function it_is_a_query_builder(): void
    {
        $this->shouldImplement(QueryBuilderInterface::class);
    }

    function it_builds_query(
        QueryBuilderInterface $containsNameQueryBuilder,
        AbstractQuery $productsQuery
    ): void {
        $containsNameQueryBuilder->buildQuery([])->willReturn($productsQuery);

        $this->buildQuery([])->shouldBeAnInstanceOf(BoolQuery::class);
    }
}

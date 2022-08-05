<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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

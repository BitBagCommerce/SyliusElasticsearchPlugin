<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\ProductOptionsByTaxonQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use PhpSpec\ObjectBehavior;

final class ProductOptionsByTaxonQueryBuilderSpec extends ObjectBehavior
{
    function let(QueryBuilderInterface $hasTaxonQueryBuilder): void
    {
        $this->beConstructedWith($hasTaxonQueryBuilder);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductOptionsByTaxonQueryBuilder::class);
    }

    function it_implements_query_builder_interface(): void
    {
        $this->shouldHaveType(QueryBuilderInterface::class);
    }

    function it_builds_query(
        QueryBuilderInterface $hasTaxonQueryBuilder,
        AbstractQuery $taxonQuery
    ): void {
        $hasTaxonQueryBuilder->buildQuery([])->willReturn($taxonQuery);

        $this->buildQuery([])->shouldBeAnInstanceOf(BoolQuery::class);
    }
}

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

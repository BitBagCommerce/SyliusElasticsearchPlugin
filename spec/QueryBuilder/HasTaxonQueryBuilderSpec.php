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

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\HasTaxonQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query\Terms;
use PhpSpec\ObjectBehavior;

final class HasTaxonQueryBuilderSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedWith('taxons_property');
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(HasTaxonQueryBuilder::class);
    }

    function it_implements_query_builder_interface(): void
    {
        $this->shouldHaveType(QueryBuilderInterface::class);
    }

    function it_builds_query(): void
    {
        $this->buildQuery([
            'taxons_property' => 'book',
        ])->shouldBeAnInstanceOf(Terms::class);
    }

    function it_builds_returned_null_if_property_is_null(): void
    {
        $this->buildQuery(['taxons_property' => null])->shouldBeEqualTo(null);
    }
}

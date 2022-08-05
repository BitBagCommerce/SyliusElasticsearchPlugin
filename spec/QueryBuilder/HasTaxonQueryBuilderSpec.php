<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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

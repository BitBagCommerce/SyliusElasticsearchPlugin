<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\IsEnabledQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query\Term;
use PhpSpec\ObjectBehavior;

final class IsEnabledQueryBuilderSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedWith('enabled');
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(IsEnabledQueryBuilder::class);
    }

    function it_implements_query_builder_interface(): void
    {
        $this->shouldHaveType(QueryBuilderInterface::class);
    }

    function it_builds_query(): void
    {
        $this->buildQuery([])->shouldBeAnInstanceOf(Term::class);
    }
}

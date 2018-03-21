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

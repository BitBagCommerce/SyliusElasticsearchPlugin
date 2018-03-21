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

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\ContainsNameQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query\Match;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class ContainsNameQueryBuilderSpec extends ObjectBehavior
{
    function let(
        LocaleContextInterface $localeContext,
        ConcatedNameResolverInterface $productNameNameResolver
    ): void {
        $this->beConstructedWith(
            $localeContext,
            $productNameNameResolver,
            'name_property'
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ContainsNameQueryBuilder::class);
    }

    function it_implements_query_builder_interface(): void
    {
        $this->shouldHaveType(QueryBuilderInterface::class);
    }

    function it_builds_query(
        LocaleContextInterface $localeContext,
        ConcatedNameResolverInterface $productNameNameResolver
    ): void {
        $localeContext->getLocaleCode()->willReturn('en');

        $productNameNameResolver->resolvePropertyName('en')->willReturn('en');

        $this->buildQuery(['name_property' => 'Book'])->shouldBeAnInstanceOf(Match::class);
    }

    function it_builds_returned_null_if_property_is_null(
        LocaleContextInterface $localeContext,
        ConcatedNameResolverInterface $productNameNameResolver
    ): void {
        $localeContext->getLocaleCode()->willReturn('en');

        $productNameNameResolver->resolvePropertyName('en')->willReturn('en');

        $this->buildQuery(['name_property' => null])->shouldBeEqualTo(null);
    }
}

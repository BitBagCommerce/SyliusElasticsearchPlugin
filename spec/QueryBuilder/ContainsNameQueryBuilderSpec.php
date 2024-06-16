<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\SearchPropertyNameResolverRegistryInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\ContainsNameQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query\MultiMatch;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class ContainsNameQueryBuilderSpec extends ObjectBehavior
{
    function let(
        LocaleContextInterface $localeContext,
        SearchPropertyNameResolverRegistryInterface $searchPropertyNameResolverRegistry,
        ConcatedNameResolverInterface $productNameNameResolver
    ): void {
        $this->beConstructedWith(
            $localeContext,
            $searchPropertyNameResolverRegistry,
            'AUTO'
        );

        $searchPropertyNameResolverRegistry->getPropertyNameResolvers()->willReturn([$productNameNameResolver]);
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
        SearchPropertyNameResolverRegistryInterface $searchPropertyNameResolverRegistry,
        ConcatedNameResolverInterface $productNameNameResolver
    ): void {
        $localeContext->getLocaleCode()->willReturn('en');

        $productNameNameResolver->resolvePropertyName('en')->willReturn('name_en');

        $searchPropertyNameResolverRegistry->getPropertyNameResolvers()->willReturn([$productNameNameResolver]);

        $query = $this->buildQuery(['name' => 'Book']);
        $query->shouldBeAnInstanceOf(MultiMatch::class);
    }

    function it_returns_null_when_no_query(
        LocaleContextInterface $localeContext,
        SearchPropertyNameResolverRegistryInterface $searchPropertyNameResolverRegistry,
        ConcatedNameResolverInterface $productNameNameResolver
    ): void {
        $localeContext->getLocaleCode()->willReturn('en');

        $productNameNameResolver->resolvePropertyName('en')->willReturn('name_en');

        $searchPropertyNameResolverRegistry->getPropertyNameResolvers()->willReturn([$productNameNameResolver]);

        $this->buildQuery([])->shouldReturn(null);
    }
}

<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\ContainsNameQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query\MatchQuery;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class ContainsNameQueryBuilderSpec extends ObjectBehavior
{
    public function let(
        LocaleContextInterface $localeContext,
        ConcatedNameResolverInterface $productNameNameResolver
    ): void {
        $this->beConstructedWith(
            $localeContext,
            $productNameNameResolver,
            'name_property'
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ContainsNameQueryBuilder::class);
    }

    public function it_implements_query_builder_interface(): void
    {
        $this->shouldHaveType(QueryBuilderInterface::class);
    }

    public function it_builds_query(
        LocaleContextInterface $localeContext,
        ConcatedNameResolverInterface $productNameNameResolver
    ): void {
        $localeContext->getLocaleCode()->willReturn('en');

        $productNameNameResolver->resolvePropertyName('en')->willReturn('en');

        $this->buildQuery(['name_property' => 'Book'])->shouldBeAnInstanceOf(MatchQuery::class);
    }

    public function it_builds_returned_null_if_property_is_null(
        LocaleContextInterface $localeContext,
        ConcatedNameResolverInterface $productNameNameResolver
    ): void {
        $localeContext->getLocaleCode()->willReturn('en');

        $productNameNameResolver->resolvePropertyName('en')->willReturn('en');

        $this->buildQuery(['name_property' => null])->shouldBeEqualTo(null);
    }
}

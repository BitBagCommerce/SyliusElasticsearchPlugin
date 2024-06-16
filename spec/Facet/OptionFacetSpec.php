<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Facet;

use BitBag\SyliusElasticsearchPlugin\Facet\FacetInterface;
use BitBag\SyliusElasticsearchPlugin\Facet\OptionFacet;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Aggregation\Terms;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Product\Model\ProductOptionInterface;

final class OptionFacetSpec extends ObjectBehavior
{
    function let(ConcatedNameResolverInterface $optionNameResolver, ProductOptionInterface $productOption): void
    {
        $optionCode = 'SUPPLY';
        $optionNameResolver->resolvePropertyName('SUPPLY')->willReturn('option_SUPPLY');
        $productOption->setCurrentLocale('en_US');
        $productOption->getName()->willReturn('Supply');
        $productOption->getCode()->willReturn($optionCode);
        $this->beConstructedWith($optionNameResolver, $productOption, $optionCode);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(OptionFacet::class);
    }

    function it_implements_facet_interface(): void
    {
        $this->shouldHaveType(FacetInterface::class);
    }

    function it_returns_terms_aggregation(): void
    {
        $expectedAggregation = new Terms('');
        $expectedAggregation->setField('option_SUPPLY.keyword');

        $this->getAggregation()->shouldBeLike($expectedAggregation);
    }

    function it_returns_terms_query(): void
    {
        $expectedQuery = new \Elastica\Query\Terms('option_SUPPLY.keyword', ['selected', 'values']);

        $this->getQuery(['selected', 'values'])->shouldBeLike($expectedQuery);
    }

    function it_returns_bucket_label(): void
    {
        $this->getBucketLabel(['key' => 'option_value', 'doc_count' => 3])->shouldReturn('Option Value (3)');
    }

    function it_returns_option_name_as_facet_label(): void
    {
        $this->getLabel()->shouldReturn('Supply');
    }
}

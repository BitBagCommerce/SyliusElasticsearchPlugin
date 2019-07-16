<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Facet;

use BitBag\SyliusElasticsearchPlugin\Facet\FacetInterface;
use BitBag\SyliusElasticsearchPlugin\Facet\OptionFacet;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Aggregation\Terms;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Product\Model\ProductOption;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class OptionFacetSpec extends ObjectBehavior
{
    function let(ConcatedNameResolverInterface $optionNameResolver, RepositoryInterface $productOptionRepository)
    {
        $optionCode = 'SUPPLY';
        $optionNameResolver->resolvePropertyName('SUPPLY')->willReturn('option_SUPPLY');
        $productOption = new ProductOption();
        $productOption->setCurrentLocale('en_US');
        $productOption->setName('Supply');
        $productOptionRepository->findOneBy(['code' => 'SUPPLY'])->willReturn($productOption);
        $this->beConstructedWith($optionNameResolver, $productOptionRepository, $optionCode);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(OptionFacet::class);
    }

    function it_implements_facet_interface()
    {
        $this->shouldHaveType(FacetInterface::class);
    }

    function it_returns_terms_aggregation()
    {
        $expectedAggregation = new Terms('');
        $expectedAggregation->setField('option_SUPPLY.keyword');

        $this->getAggregation()->shouldBeLike($expectedAggregation);
    }

    function it_returns_terms_query()
    {
        $expectedQuery = new \Elastica\Query\Terms('option_SUPPLY.keyword', ['selected', 'values']);

        $this->getQuery(['selected', 'values'])->shouldBeLike($expectedQuery);
    }

    function it_returns_bucket_label()
    {
        $this->getBucketLabel(['key' => 'option_value', 'doc_count' => 3])->shouldReturn('Option Value (3)');
    }

    function it_returns_option_name_as_facet_label()
    {
        $this->getLabel()->shouldReturn('Supply');
    }
}

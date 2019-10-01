<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Facet;

use BitBag\SyliusElasticsearchPlugin\Facet\AttributeFacet;
use BitBag\SyliusElasticsearchPlugin\Facet\FacetInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Aggregation\Terms;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Attribute\Model\Attribute;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class AttributeFacetSpec extends ObjectBehavior
{
    function let(
        ConcatedNameResolverInterface $attributeNameResolver,
        RepositoryInterface $attributeRepository
    ): void {
        $attributeNameResolver->resolvePropertyName('attribute_code')->willReturn('attribute_attribute_code');
        $this->beConstructedWith($attributeNameResolver, $attributeRepository, 'attribute_code');
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AttributeFacet::class);
    }

    function it_implements_facet_interface(): void
    {
        $this->shouldHaveType(FacetInterface::class);
    }

    function it_returns_terms_aggregation(): void
    {
        $expectedAggregation = new Terms('');
        $expectedAggregation->setField('attribute_attribute_code.keyword');

        $this->getAggregation()->shouldBeLike($expectedAggregation);
    }

    function it_returns_terms_query(): void
    {
        $selectedBuckets = ['selected_value'];
        $expectedQuery = new \Elastica\Query\Terms('attribute_attribute_code.keyword', $selectedBuckets);

        $this->getQuery($selectedBuckets)->shouldBeLike($expectedQuery);
    }

    function it_returns_bucket_label_(): void
    {
        $this->getBucketLabel(['key' => 'value_label', 'doc_count' => 3])->shouldReturn('Value Label (3)');
    }
}

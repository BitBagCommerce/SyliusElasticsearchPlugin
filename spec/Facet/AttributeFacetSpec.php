<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Facet;

use BitBag\SyliusElasticsearchPlugin\Facet\AttributeFacet;
use BitBag\SyliusElasticsearchPlugin\Facet\FacetInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Aggregation\Terms;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class AttributeFacetSpec extends ObjectBehavior
{
    public function let(
        ConcatedNameResolverInterface $attributeNameResolver,
        AttributeInterface $attribute,
        LocaleContextInterface $localeContext
    ): void {
        $attributeNameResolver->resolvePropertyName('attribute_code')->willReturn('attribute_attribute_code');
        $attribute->getCode()->willReturn('attribute_code');
        $attribute->getType()->willReturn('text');
        $this->beConstructedWith($attributeNameResolver, $attribute, $localeContext);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(AttributeFacet::class);
    }

    public function it_implements_facet_interface(): void
    {
        $this->shouldHaveType(FacetInterface::class);
    }

    public function it_returns_terms_aggregation(LocaleContextInterface $localeContext): void
    {
        $localeContext->getLocaleCode()->willReturn('en');
        $expectedAggregation = new Terms('');
        $expectedAggregation->setField('attribute_attribute_code_en.keyword');

        $this->getAggregation()->shouldBeLike($expectedAggregation);
    }

    public function it_returns_terms_query(LocaleContextInterface $localeContext): void
    {
        $localeContext->getLocaleCode()->willReturn('en');

        $selectedBuckets = ['selected_value'];
        $expectedQuery = new \Elastica\Query\Terms('attribute_attribute_code_en.keyword', $selectedBuckets);

        $this->getQuery($selectedBuckets)->shouldBeLike($expectedQuery);
    }

    public function it_returns_bucket_label_(): void
    {
        $this->getBucketLabel(['key' => 'value_label', 'doc_count' => 3])->shouldReturn('Value Label (3)');
    }
}

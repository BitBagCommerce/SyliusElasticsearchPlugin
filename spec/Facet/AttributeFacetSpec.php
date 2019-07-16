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
        RepositoryInterface $attributeRepository,
        LocaleContextInterface $localeContext
    ) {
        $attributeNameResolver->resolvePropertyName('attribute_code')->willReturn('attribute_attribute_code');
        $localeContext->getLocaleCode()->willReturn('en_US');
        $this->beConstructedWith($attributeNameResolver, $attributeRepository, $localeContext, 'attribute_code');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AttributeFacet::class);
    }

    function it_implements_facet_interface()
    {
        $this->shouldHaveType(FacetInterface::class);
    }

    function it_returns_terms_aggregation()
    {
        $expectedAggregation = new Terms('');
        $expectedAggregation->setField('attribute_attribute_code.keyword');

        $this->getAggregation()->shouldBeLike($expectedAggregation);
    }

    function it_returns_terms_query()
    {
        $selectedBuckets = ['selected_value'];
        $expectedQuery = new \Elastica\Query\Terms('attribute_attribute_code.keyword', $selectedBuckets);

        $this->getQuery($selectedBuckets)->shouldBeLike($expectedQuery);
    }

    function it_returns_bucket_label_from_config_for_select_attribute(RepositoryInterface $attributeRepository)
    {
        $attribute = new Attribute();
        $attribute->setType('select');
        $attribute->setConfiguration(['choices' => ['value_id' => ['en_US' => 'Value Label']]]);
        $attributeRepository->findOneBy(['code' => 'attribute_code'])->willReturn($attribute);

        $this->getBucketLabel(['key' => 'value_id', 'doc_count' => 3])->shouldReturn('Value Label (3)');
    }

    function it_returns_human_readable_bucket_label_for_text_attribute(RepositoryInterface $attributeRepository)
    {
        $attribute = new Attribute();
        $attribute->setType('text');
        $attributeRepository->findOneBy(['code' => 'attribute_code'])->willReturn($attribute);

        $this->getBucketLabel(['key' => 'green_&_white', 'doc_count' => 3])->shouldReturn('Green & White (3)');
    }
}

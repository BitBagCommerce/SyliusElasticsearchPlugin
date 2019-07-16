<?php
declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\SearchPropertyNameResolverRegistryInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\SearchProductsQueryBuilder;
use Elastica\Query\MultiMatch;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class SearchProductsQueryBuilderSpec extends ObjectBehavior
{
    function let(
        SearchPropertyNameResolverRegistryInterface $searchPropertyNameResolverRegistry,
        LocaleContextInterface $localeContext
    ) {
        $localeContext->getLocaleCode()->willReturn('en_US');
        $searchPropertyNameResolverRegistry->getPropertyNameResolvers()->willReturn([]);
        $this->beConstructedWith($searchPropertyNameResolverRegistry, $localeContext);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SearchProductsQueryBuilder::class);
    }

    function it_implements_query_builder_interface()
    {
        $this->shouldHaveType(QueryBuilderInterface::class);
    }

    function it_throws_an_exception_if_query_is_not_present_in_data()
    {
        $this->shouldThrow(\RuntimeException::class)->during('buildQuery', [['not_relevant_key' => 'value']]);
    }

    function it_throws_an_exception_if_query_is_not_a_string()
    {
        $this->shouldThrow(\RuntimeException::class)->during('buildQuery', [['query' => new \stdClass()]]);
    }

    function it_builds_multi_match_query_with_provided_query_string()
    {
        $expectedQuery = new MultiMatch();
        $expectedQuery->setQuery('bmw');
        $expectedQuery->setFuzziness('AUTO');
        $expectedQuery->setFields([]);

        $this->buildQuery(['query' => 'bmw'])->shouldBeLike($expectedQuery);
    }

    function it_builds_multi_match_query_with_provided_query_string_and_fields_from_registry(
        SearchPropertyNameResolverRegistryInterface $searchPropertyNameResolverRegistry,
        ConcatedNameResolverInterface $firstPropertyNameResolver,
        ConcatedNameResolverInterface $secondPropertyNameResolver
    ) {
        $firstPropertyNameResolver->resolvePropertyName('en_US')->shouldBeCalled()->willReturn('property_1_en_us');
        $secondPropertyNameResolver->resolvePropertyName('en_US')->shouldBeCalled()->willReturn('property_2_en_us');
        $searchPropertyNameResolverRegistry->getPropertyNameResolvers()->willReturn(
            [$firstPropertyNameResolver, $secondPropertyNameResolver]
        );
        $expectedQuery = new MultiMatch();
        $expectedQuery->setQuery('bmw');
        $expectedQuery->setFuzziness('AUTO');
        $expectedQuery->setFields(['property_1_en_us', 'property_2_en_us']);

        $this->buildQuery(['query' => 'bmw'])->shouldBeLike($expectedQuery);
    }
}

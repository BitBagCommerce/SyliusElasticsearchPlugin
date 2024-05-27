<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\SearchPropertyNameResolverRegistryInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\SiteWideProductsQueryBuilder;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class SiteWideProductsQueryBuilderSpec extends ObjectBehavior
{
    private $isEnabeldQuery;

    private $hasChannelQuery;

    private $fuzziness;

    function let(
        SearchPropertyNameResolverRegistryInterface $searchPropertyNameResolverRegistry,
        LocaleContextInterface $localeContext,
        QueryBuilderInterface $isEnabledQueryBuilder,
        QueryBuilderInterface $hasChannelQueryBuilder
    ): void {
        $localeContext->getLocaleCode()->willReturn('en_US');
        $searchPropertyNameResolverRegistry->getPropertyNameResolvers()->willReturn([]);
        $this->isEnabeldQuery = new Term();
        $this->isEnabeldQuery->setTerm('enabled', true);
        $isEnabledQueryBuilder->buildQuery([])->willReturn($this->isEnabeldQuery);
        $this->hasChannelQuery = new Terms('channels');
        $this->hasChannelQuery->setTerms(['web_us']);
        $hasChannelQueryBuilder->buildQuery([])->willReturn($this->hasChannelQuery);
        $this->fuzziness = 'AUTO';
        $this->beConstructedWith(
            $searchPropertyNameResolverRegistry,
            $localeContext,
            $isEnabledQueryBuilder,
            $hasChannelQueryBuilder,
            $this->fuzziness
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(SiteWideProductsQueryBuilder::class);
    }

    function it_implements_query_builder_interface(): void
    {
        $this->shouldHaveType(QueryBuilderInterface::class);
    }

    function it_throws_an_exception_if_query_is_not_present_in_data(): void
    {
        $this->shouldThrow(\RuntimeException::class)->during('buildQuery', [['not_relevant_key' => 'value']]);
    }

    function it_throws_an_exception_if_query_is_not_a_string(): void
    {
        $this->shouldThrow(\RuntimeException::class)->during('buildQuery', [['query' => new \stdClass()]]);
    }

    function it_builds_multi_match_query_with_provided_query_string(): void
    {
        $expectedMultiMatch = new MultiMatch();
        $expectedMultiMatch->setQuery('bmw');
        $expectedMultiMatch->setFuzziness($this->fuzziness);
        $expectedMultiMatch->setFields([]);
        $expectedQuery = new BoolQuery();
        $expectedQuery->addMust($expectedMultiMatch);
        $expectedQuery->addFilter($this->isEnabeldQuery);
        $expectedQuery->addFilter($this->hasChannelQuery);

        $this->buildQuery(['query' => 'bmw'])->shouldBeLike($expectedQuery);
    }

    function it_builds_multi_match_query_with_provided_query_string_and_fields_from_registry(
        SearchPropertyNameResolverRegistryInterface $searchPropertyNameResolverRegistry,
        ConcatedNameResolverInterface $firstPropertyNameResolver,
        ConcatedNameResolverInterface $secondPropertyNameResolver
    ): void {
        $firstPropertyNameResolver->resolvePropertyName('en_US')->shouldBeCalled()->willReturn('property_1_en_us');
        $secondPropertyNameResolver->resolvePropertyName('en_US')->shouldBeCalled()->willReturn('property_2_en_us');
        $searchPropertyNameResolverRegistry->getPropertyNameResolvers()->willReturn(
            [$firstPropertyNameResolver, $secondPropertyNameResolver]
        );
        $expectedMultiMatch = new MultiMatch();
        $expectedMultiMatch->setQuery('bmw');
        $expectedMultiMatch->setFuzziness($this->fuzziness);
        $expectedMultiMatch->setFields(['property_1_en_us', 'property_2_en_us']);
        $expectedQuery = new BoolQuery();
        $expectedQuery->addMust($expectedMultiMatch);
        $expectedQuery->addFilter($this->isEnabeldQuery);
        $expectedQuery->addFilter($this->hasChannelQuery);

        $this->buildQuery(['query' => 'bmw'])->shouldBeLike($expectedQuery);
    }
}

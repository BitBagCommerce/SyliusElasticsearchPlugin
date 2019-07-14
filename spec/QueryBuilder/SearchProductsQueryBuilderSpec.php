<?php
declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\SearchProductsQueryBuilder;
use Elastica\Query\MultiMatch;
use PhpSpec\ObjectBehavior;

class SearchProductsQueryBuilderSpec extends ObjectBehavior
{
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
        $this->buildQuery(['query' => 'bmw'])->shouldBeLike($expectedQuery);
    }
}

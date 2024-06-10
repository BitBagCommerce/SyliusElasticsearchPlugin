<?php
/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */
 
declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\SiteWideProductsQueryBuilder;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use PhpSpec\ObjectBehavior;

final class SiteWideProductsQueryBuilderSpec extends ObjectBehavior
{
    private AbstractQuery $isEnabeldQuery;

    private AbstractQuery $hasChannelQuery;

    private AbstractQuery $containsNameQuery;

    function let(
        QueryBuilderInterface $isEnabledQueryBuilder,
        QueryBuilderInterface $hasChannelQueryBuilder,
        QueryBuilderInterface $containsNameQueryBuilder,
    ): void {
        $this->isEnabeldQuery = new Term();
        $this->isEnabeldQuery->setTerm('enabled', true);
        $isEnabledQueryBuilder->buildQuery([])->willReturn($this->isEnabeldQuery);

        $this->hasChannelQuery = new Terms('channels');
        $this->hasChannelQuery->setTerms(['web_us']);
        $hasChannelQueryBuilder->buildQuery([])->willReturn($this->hasChannelQuery);

        $this->containsNameQuery = new MultiMatch();
        $this->containsNameQuery->setQuery('bmw');
        $containsNameQueryBuilder->buildQuery(['query' => 'bmw'])->willReturn($this->containsNameQuery);

        $this->beConstructedWith(
            $isEnabledQueryBuilder,
            $hasChannelQueryBuilder,
            $containsNameQueryBuilder
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

    function it_builds_multi_match_query_with_provided_query_string(): void
    {
        $expectedQuery = new BoolQuery();
        $expectedQuery->addMust($this->isEnabeldQuery);
        $expectedQuery->addMust($this->hasChannelQuery);
        $expectedQuery->addMust($this->containsNameQuery);

        $this->buildQuery(['query' => 'bmw'])->shouldBeLike($expectedQuery);
    }
}

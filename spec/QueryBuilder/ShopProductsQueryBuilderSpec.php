<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\ShopProductsQueryBuilder;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use PhpSpec\ObjectBehavior;

final class ShopProductsQueryBuilderSpec extends ObjectBehavior
{
    function let(
        QueryBuilderInterface $isEnabledQueryBuilder,
        QueryBuilderInterface $hasChannelQueryBuilder,
        QueryBuilderInterface $containsNameQueryBuilder,
        QueryBuilderInterface $hasTaxonQueryBuilder,
        QueryBuilderInterface $hasOptionsQueryBuilder,
        QueryBuilderInterface $hasAttributesQueryBuilder,
        QueryBuilderInterface $hasPriceBetweenQueryBuilder
    ): void {
        $this->beConstructedWith(
            $isEnabledQueryBuilder,
            $hasChannelQueryBuilder,
            $containsNameQueryBuilder,
            $hasTaxonQueryBuilder,
            $hasOptionsQueryBuilder,
            $hasAttributesQueryBuilder,
            $hasPriceBetweenQueryBuilder,
            'option',
            'attribute'
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ShopProductsQueryBuilder::class);
    }

    function it_implements_query_builder_interface(): void
    {
        $this->shouldHaveType(QueryBuilderInterface::class);
    }

    function it_builds_query(
        QueryBuilderInterface $isEnabledQueryBuilder,
        QueryBuilderInterface $hasChannelQueryBuilder,
        QueryBuilderInterface $containsNameQueryBuilder,
        QueryBuilderInterface $hasTaxonQueryBuilder,
        QueryBuilderInterface $hasOptionsQueryBuilder,
        QueryBuilderInterface $hasAttributesQueryBuilder,
        QueryBuilderInterface $hasPriceBetweenQueryBuilder,
        AbstractQuery $query
    ): void {
        $containsNameQueryBuilder->buildQuery(['option' => ['XL'], 'attribute' => ['Red']])->willReturn($query);
        $isEnabledQueryBuilder->buildQuery(['option' => ['XL'], 'attribute' => ['Red']])->willReturn($query);
        $hasTaxonQueryBuilder->buildQuery(['option' => ['XL'], 'attribute' => ['Red']])->willReturn($query);
        $hasChannelQueryBuilder->buildQuery(['option' => ['XL'], 'attribute' => ['Red']])->willReturn($query);
        $hasOptionsQueryBuilder->buildQuery(['option' => 'option', 'option_values' => ['XL']])->willReturn($query);
        $hasAttributesQueryBuilder->buildQuery(['attribute' => 'attribute', 'attribute_values' => ['Red']])->willReturn($query);
        $hasPriceBetweenQueryBuilder->buildQuery(['option' => ['XL'], 'attribute' => ['Red']])->willReturn($query);

        $this->buildQuery([
            'option' => ['XL'],
            'attribute' => ['Red'],
        ])->shouldBeAnInstanceOf(BoolQuery::class);
    }
}

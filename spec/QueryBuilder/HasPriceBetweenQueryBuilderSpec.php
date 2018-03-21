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

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\PriceNameResolverInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\HasPriceBetweenQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query\Range;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;

final class HasPriceBetweenQueryBuilderSpec extends ObjectBehavior
{
    function let(
        PriceNameResolverInterface $priceNameResolver,
        ConcatedNameResolverInterface $channelPricingNameResolver,
        ChannelContextInterface $channelContext
    ): void {
        $this->beConstructedWith(
            $priceNameResolver,
            $channelPricingNameResolver,
            $channelContext
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(HasPriceBetweenQueryBuilder::class);
    }

    function it_implements_query_builder_interface(): void
    {
        $this->shouldHaveType(QueryBuilderInterface::class);
    }

    function it_builds_query(
        PriceNameResolverInterface $priceNameResolver,
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        ConcatedNameResolverInterface $channelPricingNameResolver
    ): void {
        $channel->getCode()->willReturn('web');

        $channelContext->getChannel()->willReturn($channel);

        $priceNameResolver->resolveMinPriceName()->willReturn('min_price');
        $priceNameResolver->resolveMaxPriceName()->willReturn('max_price');

        $channelPricingNameResolver->resolvePropertyName('web')->willReturn('web');

        $this->buildQuery([
            'min_price' => '200',
            'max_price' => '1000',
        ])->shouldBeAnInstanceOf(Range::class);
    }
}

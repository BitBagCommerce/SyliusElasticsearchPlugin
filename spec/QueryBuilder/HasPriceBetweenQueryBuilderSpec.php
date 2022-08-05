<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
use Sylius\Component\Currency\Context\CurrencyContextInterface;
use Sylius\Component\Currency\Converter\CurrencyConverterInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;

final class HasPriceBetweenQueryBuilderSpec extends ObjectBehavior
{
    function let(
        PriceNameResolverInterface $priceNameResolver,
        ConcatedNameResolverInterface $channelPricingNameResolver,
        ChannelContextInterface $channelContext,
        CurrencyContextInterface $currencyContext,
        CurrencyConverterInterface $currencyConverter
    ): void {
        $this->beConstructedWith(
            $priceNameResolver,
            $channelPricingNameResolver,
            $channelContext,
            $currencyContext,
            $currencyConverter
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
        CurrencyContextInterface $currencyContext,
        CurrencyInterface $currency,
        ConcatedNameResolverInterface $channelPricingNameResolver
    ): void {
        $channel->getCode()->willReturn('web');
        $channelContext->getChannel()->willReturn($channel);
        $priceNameResolver->resolveMinPriceName()->willReturn('min_price');
        $priceNameResolver->resolveMaxPriceName()->willReturn('max_price');
        $channel->getBaseCurrency()->willReturn($currency);
        $currency->getCode()->willReturn('USD');
        $currencyContext->getCurrencyCode()->willReturn('USD');

        $channelPricingNameResolver->resolvePropertyName('web')->willReturn('web');

        $this->buildQuery([
            'min_price' => '200',
            'max_price' => '1000',
        ])->shouldBeAnInstanceOf(Range::class);
    }

    function it_converts_fractional_currency_properly(
        PriceNameResolverInterface $priceNameResolver,
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        CurrencyContextInterface $currencyContext,
        CurrencyInterface $currency,
        ConcatedNameResolverInterface $channelPricingNameResolver
    ): void {
        $channel->getCode()->willReturn('web');
        $channelContext->getChannel()->willReturn($channel);
        $priceNameResolver->resolveMinPriceName()->willReturn('min_price');
        $priceNameResolver->resolveMaxPriceName()->willReturn('max_price');
        $channel->getBaseCurrency()->willReturn($currency);
        $currency->getCode()->willReturn('USD');
        $currencyContext->getCurrencyCode()->willReturn('USD');

        $channelPricingNameResolver->resolvePropertyName('web')->willReturn('web');

        $range = $this->buildQuery([
            'min_price' => '1,23',
            'max_price' => '1000,51',
        ]);

        $range->getParam('web')->shouldReturn(
            [
                'gte' => 123,
                'lte' => 100051,
            ]
        );
    }

    function it_build_query_with_max_price(
        PriceNameResolverInterface $priceNameResolver,
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        CurrencyContextInterface $currencyContext,
        CurrencyInterface $currency,
        ConcatedNameResolverInterface $channelPricingNameResolver
    ): void {
        $channel->getCode()->willReturn('web');
        $channelContext->getChannel()->willReturn($channel);
        $priceNameResolver->resolveMinPriceName()->willReturn('min_price');
        $priceNameResolver->resolveMaxPriceName()->willReturn('max_price');
        $channel->getBaseCurrency()->willReturn($currency);
        $currency->getCode()->willReturn('USD');
        $currencyContext->getCurrencyCode()->willReturn('USD');

        $channelPricingNameResolver->resolvePropertyName('web')->willReturn('web');

        $range = $this->buildQuery([
            'max_price' => '110,51',
        ]);

        $range->getParam('web')->shouldReturn(
            [
                'lte' => 11051,
            ]
        );
    }

    function it_build_query_with_min_price(
        PriceNameResolverInterface $priceNameResolver,
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        CurrencyContextInterface $currencyContext,
        CurrencyInterface $currency,
        ConcatedNameResolverInterface $channelPricingNameResolver
    ): void {
        $channel->getCode()->willReturn('web');
        $channelContext->getChannel()->willReturn($channel);
        $priceNameResolver->resolveMinPriceName()->willReturn('min_price');
        $priceNameResolver->resolveMaxPriceName()->willReturn('max_price');
        $channel->getBaseCurrency()->willReturn($currency);
        $currency->getCode()->willReturn('USD');
        $currencyContext->getCurrencyCode()->willReturn('USD');

        $channelPricingNameResolver->resolvePropertyName('web')->willReturn('web');

        $range = $this->buildQuery([
            'min_price' => '133,22',
        ]);

        $range->getParam('web')->shouldReturn(
            [
                'gte' => 13322,
            ]
        );
    }

    function it_build_query_without_price_param(
        PriceNameResolverInterface $priceNameResolver,
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        CurrencyContextInterface $currencyContext,
        CurrencyInterface $currency,
        ConcatedNameResolverInterface $channelPricingNameResolver
    ): void {
        $channel->getCode()->willReturn('web');
        $channelContext->getChannel()->willReturn($channel);
        $priceNameResolver->resolveMinPriceName()->willReturn('min_price');
        $priceNameResolver->resolveMaxPriceName()->willReturn('max_price');
        $channel->getBaseCurrency()->willReturn($currency);
        $currency->getCode()->willReturn('USD');
        $currencyContext->getCurrencyCode()->willReturn('USD');

        $channelPricingNameResolver->resolvePropertyName('web')->willReturn('web');

        $range = $this->buildQuery([]);

        $range->shouldBeNull();
    }
}

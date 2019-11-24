<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Transformer\Product;

use BitBag\SyliusElasticsearchPlugin\Transformer\Product\TransformerInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ChannelPricingInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;

final class ChannelPricingTransformerSpec extends ObjectBehavior
{
    function let(
        ChannelContextInterface $channelContext,
        ProductVariantResolverInterface $productVariantResolver,
        MoneyFormatterInterface $moneyFormatter
    ): void {
        $this->beConstructedWith($channelContext, $productVariantResolver, $moneyFormatter);
    }

    function it_is_a_transformer(): void
    {
        $this->shouldImplement(TransformerInterface::class);
    }

    function it_transforms_product_channel_prices_into_formatted_price(
        ChannelContextInterface $channelContext,
        ProductVariantResolverInterface $productVariantResolver,
        MoneyFormatterInterface $moneyFormatter,
        CurrencyInterface $currency,
        ChannelInterface $channel,
        ProductVariantInterface $productVariant,
        ProductInterface $product,
        ChannelPricingInterface $channelPricing
    ): void {
        $currency->getCode()->willReturn('USD');
        $channel->getBaseCurrency()->willReturn($currency);
        $channelContext->getChannel()->willReturn($channel);
        $productVariantResolver->getVariant($product)->willReturn($productVariant);
        $channelPricing->getPrice()->willReturn(10);
        $productVariant->getChannelPricingForChannel($channel)->willReturn($channelPricing);

        $moneyFormatter->format(10, 'USD');

        $this->transform($product);
    }

    function it_returns_null_when_product_doeas_not_have_any_variant(
        ChannelContextInterface $channelContext,
        ProductVariantResolverInterface $productVariantResolver,
        MoneyFormatterInterface $moneyFormatter,
        CurrencyInterface $currency,
        ChannelInterface $channel,
        ProductVariantInterface $productVariant,
        ProductInterface $product,
        ChannelPricingInterface $channelPricing
    ): void {
        $currency->getCode()->willReturn('USD');
        $channel->getBaseCurrency()->willReturn($currency);
        $channelContext->getChannel()->willReturn($channel);
        $productVariantResolver->getVariant($product)->willReturn(null);

        $this->transform($product)->shouldBeNull();
    }
}

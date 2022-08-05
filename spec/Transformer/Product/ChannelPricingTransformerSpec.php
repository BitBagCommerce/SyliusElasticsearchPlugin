<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;

final class ChannelPricingTransformerSpec extends ObjectBehavior
{
    function let(
        ChannelContextInterface $channelContext,
        LocaleContextInterface $localeContext,
        ProductVariantResolverInterface $productVariantResolver,
        MoneyFormatterInterface $moneyFormatter
    ): void {
        $this->beConstructedWith($channelContext, $localeContext, $productVariantResolver, $moneyFormatter);
    }

    function it_is_a_transformer(): void
    {
        $this->shouldImplement(TransformerInterface::class);
    }

    function it_transforms_product_channel_prices_into_formatted_price(
        ChannelContextInterface $channelContext,
        LocaleContextInterface $localeContext,
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
        $localeContext->getLocaleCode()->willReturn('en_US');
        $productVariantResolver->getVariant($product)->willReturn($productVariant);
        $channelPricing->getPrice()->willReturn(10);
        $productVariant->getChannelPricingForChannel($channel)->willReturn($channelPricing);

        $moneyFormatter->format(10, 'USD', 'en_US');

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

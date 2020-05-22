<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Transformer\Product;

use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;

final class ChannelPricingTransformer implements TransformerInterface
{
    /** @var ChannelContextInterface */
    private $channelContext;

    /** @var LocaleContextInterface */
    private $localeContext;

    /** @var ProductVariantResolverInterface */
    private $productVariantResolver;

    /** @var MoneyFormatterInterface */
    private $moneyFormatter;

    public function __construct(
        ChannelContextInterface $channelContext,
        LocaleContextInterface $localeContext,
        ProductVariantResolverInterface $productVariantResolver,
        MoneyFormatterInterface $moneyFormatter
    ) {
        $this->channelContext = $channelContext;
        $this->localeContext = $localeContext;
        $this->productVariantResolver = $productVariantResolver;
        $this->moneyFormatter = $moneyFormatter;
    }

    public function transform(ProductInterface $product): ?string
    {
        /** @var ChannelInterface|null $channel */
        $channel = $this->channelContext->getChannel();

        if (null === $channelBaseCurrency = $channel->getBaseCurrency()) {
            throw new \RuntimeException('No channel currency configured');
        }

        /** @var ProductVariantInterface|null $productVariant */
        $productVariant = $this->productVariantResolver->getVariant($product);

        if (null === $productVariant) {
            return null;
        }

        $productVariantPricing = $productVariant->getChannelPricingForChannel($channel);

        if (null === $productVariantPricing) {
            return null;
        }

        return $this->moneyFormatter->format($productVariantPricing->getPrice(), $channelBaseCurrency->getCode(), $this->localeContext->getLocaleCode());
    }
}

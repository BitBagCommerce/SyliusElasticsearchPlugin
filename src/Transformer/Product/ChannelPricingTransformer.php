<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Transformer\Product;

use RuntimeException;
use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;
use Webmozart\Assert\Assert;

final class ChannelPricingTransformer implements TransformerInterface
{
    public function __construct(
        private ChannelContextInterface $channelContext,
        private LocaleContextInterface $localeContext,
        private ProductVariantResolverInterface $productVariantResolver,
        private MoneyFormatterInterface $moneyFormatter
    ) {
    }

    public function transform(ProductInterface $product): ?string
    {
        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();

        if (null === $channelBaseCurrency = $channel->getBaseCurrency()) {
            throw new RuntimeException('No channel currency configured');
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

        Assert::integer($productVariantPricing->getPrice());
        Assert::string($channelBaseCurrency->getCode());

        return $this->moneyFormatter->format(
            $productVariantPricing->getPrice(),
            $channelBaseCurrency->getCode(),
            $this->localeContext->getLocaleCode()
        );
    }
}

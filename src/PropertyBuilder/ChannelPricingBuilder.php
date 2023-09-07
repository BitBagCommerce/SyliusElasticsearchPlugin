<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

final class ChannelPricingBuilder extends AbstractBuilder
{
    private ConcatedNameResolverInterface $channelPricingNameResolver;

    public function __construct(ConcatedNameResolverInterface $channelPricingNameResolver)
    {
        $this->channelPricingNameResolver = $channelPricingNameResolver;
    }

    public function consumeEvent(PostTransformEvent $event): void
    {
        $this->buildProperty(
            $event,
            ProductInterface::class,
            function (ProductInterface $product, Document $document): void {
                if (0 === $product->getVariants()->count()) {
                    return;
                }

                /** @var array<string, array<int>> $pricesPerChannel */
                $pricesPerChannel = [];

                /** @var ProductVariantInterface $productVariant */
                foreach ($product->getVariants() as $productVariant) {
                    foreach ($productVariant->getChannelPricings() as $variantChannelPricing) {
                        $channelCode = $variantChannelPricing->getChannelCode();
                        if (null === $channelCode) {
                            continue;
                        }
                        $elasticFieldName = $this->channelPricingNameResolver->resolvePropertyName($channelCode);
                        $channelPrice = $variantChannelPricing->getPrice();

                        if ($channelPrice !== null) {
                            $pricesPerChannel[$elasticFieldName][] = $channelPrice;
                        }
                    }
                }

                foreach ($pricesPerChannel as $elasticFieldName => $channelVariantPrices) {
                    $document->set($elasticFieldName, array_unique($channelVariantPrices));
                }
            }
        );
    }
}

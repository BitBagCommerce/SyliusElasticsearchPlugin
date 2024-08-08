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
    public function __construct(
        private ConcatedNameResolverInterface $channelPricingNameResolver
    ) {
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

                /** @var ProductVariantInterface $productVariant */
                $productVariant = $product->getVariants()->first();

                foreach ($productVariant->getChannelPricings() as $channelPricing) {
                    /** @var string $channelCode */
                    $channelCode = $channelPricing->getChannelCode();

                    $propertyName = $this->channelPricingNameResolver
                        ->resolvePropertyName($channelCode);

                    $document->set($propertyName, $channelPricing->getPrice());
                }
            }
        );
    }
}

<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
    /** @var ConcatedNameResolverInterface */
    private $channelPricingNameResolver;

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

                /** @var ProductVariantInterface $productVariant */
                $productVariant = $product->getVariants()->first();

                foreach ($productVariant->getChannelPricings() as $channelPricing) {
                    $propertyName = $this->channelPricingNameResolver->resolvePropertyName($channelPricing->getChannelCode());

                    $document->set($propertyName, $channelPricing->getPrice());
                }
            }
        );
    }
}

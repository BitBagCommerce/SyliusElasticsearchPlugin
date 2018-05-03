<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

final class ChannelPricingBuilder extends AbstractBuilder
{
    /**
     * @var ConcatedNameResolverInterface
     */
    private $channelPricingNameResolver;

    /**
     * @param ConcatedNameResolverInterface $channelPricingNameResolver
     */
    public function __construct(ConcatedNameResolverInterface $channelPricingNameResolver)
    {
        $this->channelPricingNameResolver = $channelPricingNameResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function consumeEvent(TransformEvent $event): void
    {
        $this->buildProperty($event, ProductInterface::class,
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
            });
    }
}

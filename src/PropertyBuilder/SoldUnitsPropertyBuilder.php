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

use BitBag\SyliusElasticsearchPlugin\Repository\OrderItemRepositoryInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use Sylius\Component\Core\Model\ProductInterface;

final class SoldUnitsPropertyBuilder extends AbstractBuilder
{
    private OrderItemRepositoryInterface $orderItemRepository;

    private string $soldUnitsProperty;

    public function __construct(OrderItemRepositoryInterface $orderItemRepository, string $soldUnitsProperty)
    {
        $this->orderItemRepository = $orderItemRepository;
        $this->soldUnitsProperty = $soldUnitsProperty;
    }

    public function consumeEvent(PostTransformEvent $event): void
    {
        $this->buildProperty(
            $event,
            ProductInterface::class,
            function (ProductInterface $product, Document $document): void {
                $soldUnits = 0;

                foreach ($product->getVariants() as $productVariant) {
                    $soldUnits += $this->orderItemRepository->countByVariant($productVariant);
                }

                $document->set($this->soldUnitsProperty, $soldUnits);
            }
        );
    }
}

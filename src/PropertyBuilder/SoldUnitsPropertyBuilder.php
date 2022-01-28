<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\Repository\OrderItemRepositoryInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use Sylius\Component\Core\Model\ProductInterface;

final class SoldUnitsPropertyBuilder extends AbstractBuilder
{
    /** @var OrderItemRepositoryInterface */
    private $orderItemRepository;

    /** @var string */
    private $soldUnitsProperty;

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

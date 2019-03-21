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

use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Order\Repository\OrderItemRepositoryInterface;

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

    public function consumeEvent(TransformEvent $event): void
    {
        $this->buildProperty($event, ProductInterface::class,
            function (ProductInterface $product, Document $document): void {
                $soldUnits = 0;

                foreach ($product->getVariants() as $productVariant) {
                    $soldUnits += count($this->orderItemRepository->findBy(['variant' => $productVariant]));
                }

                $document->set($this->soldUnitsProperty, $soldUnits);
            }
        );
    }
}

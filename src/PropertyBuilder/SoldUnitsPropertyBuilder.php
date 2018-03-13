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

use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Order\Repository\OrderItemRepositoryInterface;

final class SoldUnitsPropertyBuilder extends AbstractBuilder
{
    /**
     * @var OrderItemRepositoryInterface
     */
    private $orderItemRepository;

    /**
     * @var string
     */
    private $soldUnitsProperty;

    /**
     * @param OrderItemRepositoryInterface $orderItemRepository
     * @param string $soldUnitsProperty
     */
    public function __construct(OrderItemRepositoryInterface $orderItemRepository, string $soldUnitsProperty)
    {
        $this->orderItemRepository = $orderItemRepository;
        $this->soldUnitsProperty = $soldUnitsProperty;
    }

    /**
     * {@inheritdoc}
     */
    public function buildProperty(TransformEvent $event): void
    {
        /** @var ProductInterface $product */
        $product = $event->getObject();

        if (!$product instanceof ProductInterface) {
            return;
        }

        $soldUnits = 0;

        foreach ($product->getVariants() as $productVariant) {
            $soldUnits += count($this->orderItemRepository->findBy(['variant' => $productVariant]));
        }

        $event->getDocument()->set($this->soldUnitsProperty, $soldUnits);
    }
}

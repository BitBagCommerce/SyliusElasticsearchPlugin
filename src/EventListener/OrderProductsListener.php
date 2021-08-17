<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);
/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

namespace BitBag\SyliusElasticsearchPlugin\EventListener;

use BitBag\SyliusElasticsearchPlugin\Refresher\ResourceRefresherInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webmozart\Assert\Assert;

final class OrderProductsListener
{
    /** @var ResourceRefresherInterface */
    private $resourceRefresher;

    /** @var string */
    private $productPersister;

    public function __construct(ResourceRefresherInterface $resourceRefresher, string $productPersister)
    {
        $this->resourceRefresher = $resourceRefresher;
        $this->productPersister = $productPersister;
    }

    public function updateOrderProducts(GenericEvent $event): void
    {
        $order = $event->getSubject();
        Assert::isInstanceOf($order, OrderInterface::class);

        /** @var OrderItemInterface $orderItem */
        foreach ($order->getItems() as $orderItem) {
            $this->resourceRefresher->refresh($orderItem->getProduct(), $this->productPersister);
        }
    }
}

<?php

declare(strict_types=1);

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

namespace BitBag\SyliusElasticsearchPlugin\EventListener;

use BitBag\SyliusElasticsearchPlugin\Refresher\ResourceRefresherInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItem;
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

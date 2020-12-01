<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Repository;

use Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

class OrderItemRepository implements OrderItemRepositoryInterface
{
    /** @var OrderItemRepositoryInterface */
    private $baseOrderItemRepository;

    public function __construct(EntityRepository $baseOrderItemRepository)
    {
        $this->baseOrderItemRepository = $baseOrderItemRepository;
    }

    public function countByVariant(ProductVariantInterface $variant, array $orderStates = []): int
    {
        if (!$orderStates) {
            $orderStates = [OrderInterface::STATE_CANCELLED, OrderInterface::STATE_CART];
        }
        
        return (int) ($this->baseOrderItemRepository
            ->createQueryBuilder('oi')
            ->select('SUM(oi.quantity)')
            ->join('oi.order', 'o')
            ->where('oi.variant = :variant')
            ->andWhere('o.state NOT IN (:states)')
            ->setParameter('variant', $variant)
            ->setParameter('states', $orderStates)
            ->getQuery()
            ->getSingleScalarResult());
    }
}

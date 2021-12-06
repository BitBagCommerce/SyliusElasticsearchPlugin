<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
        if (empty($orderStates)) {
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

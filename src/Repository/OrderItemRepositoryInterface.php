<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Repository;

use Sylius\Component\Core\Model\ProductVariantInterface;

interface OrderItemRepositoryInterface
{
    public function countByVariant(ProductVariantInterface $variant): int;
}

<?php

declare(strict_types = 1);

namespace BitBag\SyliusElasticsearchPlugin\EntityRepository;

use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Product\Model\ProductAttributeValueInterface;

interface ProductAttributeValueRepositoryInterface
{

    /**
     * @param AttributeInterface $productAttribute
     *
     * @return array|ProductAttributeValueInterface[]
     */
    public function getUniqueAttributeValues(AttributeInterface $productAttribute): array;
}

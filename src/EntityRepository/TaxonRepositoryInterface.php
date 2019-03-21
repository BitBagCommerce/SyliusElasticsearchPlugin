<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\EntityRepository;

use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Core\Model\TaxonInterface;

interface TaxonRepositoryInterface
{
    /**
     * @param AttributeInterface $attribute
     *
     * @return TaxonInterface[]
     */
    public function getTaxonsByAttributeViaProduct(AttributeInterface $attribute): array;
}

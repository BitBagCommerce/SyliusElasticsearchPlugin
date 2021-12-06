<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder\Mapper;

use Sylius\Component\Core\Model\ProductInterface;

final class ProductTaxonsMapper implements ProductTaxonsMapperInterface
{
    public function mapToUniqueCodes(ProductInterface $product): array
    {
        $taxons = [];

        foreach ($product->getTaxons() as $taxon) {
            $taxons[] = $taxon->getCode();
        }

        return array_values(array_unique($taxons));
    }
}

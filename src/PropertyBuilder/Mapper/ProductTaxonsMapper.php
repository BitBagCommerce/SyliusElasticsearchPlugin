<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder\Mapper;

use Sylius\Component\Core\Model\ProductInterface;

final class ProductTaxonsMapper implements ProductTaxonsMapperInterface
{
    public function __construct(
        private bool $includeAllDescendants
    ) {
    }

    public function mapToUniqueCodes(ProductInterface $product): array
    {
        $taxons = [];

        foreach ($product->getTaxons() as $taxon) {
            $taxons[] = $taxon->getCode();

            if (true === $this->includeAllDescendants) {
                foreach ($taxon->getAncestors() as $ancestor) {
                    $taxons[] = $ancestor->getCode();
                }
            }
        }

        return array_values(array_unique($taxons));
    }
}
